<?php

namespace Tests\Feature;

use App\Models\Inventario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InventarioMesAnteriorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // RefreshDatabase apaga as tabelas; nunca rodar contra o banco real.
        $this->assertStringEndsWith('_test', DB::connection()->getDatabaseName());
    }

    public static function mesesDoAno(): array
    {
        $casos = [];
        foreach (range(1, 12) as $mes) {
            $casos[sprintf('mês %02d', $mes)] = [2026, $mes];
        }
        return $casos;
    }

    /**
     * @dataProvider mesesDoAno
     */
    public function test_saida_usa_o_estoque_do_inventario_do_mes_anterior(int $ano, int $mes): void
    {
        $congregacaoId = DB::table('congregacoes')->insertGetId(['nome' => 'Congregação Teste']);
        $localId = DB::table('locais')->insertGetId([
            'sigla' => 'T', 'nome' => 'Local Teste', 'congregacao_id' => $congregacaoId,
        ]);
        $publicacaoId = DB::table('publicacoes')->insertGetId(['nome' => 'Publicação Teste']);

        $anterior = Carbon::create($ano, $mes, 1)->subMonthNoOverflow();
        Inventario::create([
            'ano' => $anterior->format('Y'),
            'mes' => $anterior->format('m'),
            'congregacao_id' => $congregacaoId,
            'publicacao_id' => $publicacaoId,
            'recebido' => 0,
            'estoque' => 100,
            'saida' => 0,
        ]);
        DB::table('estoques')->insert([
            'publicacao_id' => $publicacaoId, 'local_id' => $localId, 'quantidade' => 40,
        ]);

        $usuario = User::factory()->create(['congregacao_id' => $congregacaoId]);

        $this->actingAs($usuario)
            ->withoutMiddleware()
            ->post('/inventariar')
            ->assertRedirect();

        $item = Inventario::where('ano', (string) $ano)
            ->where('mes', sprintf('%02d', $mes))
            ->where('congregacao_id', $congregacaoId)
            ->where('publicacao_id', $publicacaoId)
            ->first();

        $this->assertNotNull($item, 'O inventário do mês não foi criado.');
        $this->assertEquals(40, $item->estoque);
        $this->assertEquals(60, $item->saida, 'Saída deve ser estoque anterior (100) - estoque atual (40).');
    }
}
