<?php

namespace Tests\Feature;

use App\Models\Inventario;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InventarioProximoMesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // RefreshDatabase apaga as tabelas; nunca rodar contra o banco real.
        $this->assertStringEndsWith('_test', DB::connection()->getDatabaseName());
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public static function proximosMeses(): array
    {
        return [
            'jan' => ['2026', '01', '2026', '02'],
            'fev' => ['2026', '02', '2026', '03'],
            'ago' => ['2026', '08', '2026', '09'],
            'set' => ['2026', '09', '2026', '10'],
            'out' => ['2026', '10', '2026', '11'],
            'nov' => ['2026', '11', '2026', '12'],
            'dez' => ['2026', '12', '2027', '01'],
        ];
    }

    /**
     * @dataProvider proximosMeses
     */
    public function test_proximo_mes_depois_do_ultimo_inventario(string $ano, string $mes, string $anoEsperado, string $mesEsperado): void
    {
        $congregacaoId = $this->criaInventario($ano, $mes);

        $r = Inventario::defineAnoMesUltimoInventariado($congregacaoId);

        $this->assertSame($anoEsperado, $r['anoAInventariar']);
        $this->assertSame($mesEsperado, $r['mesAInventariar']);
    }

    public function test_usa_o_inventario_mais_recente(): void
    {
        $congregacaoId = $this->criaInventario('2025', '12');
        $this->criaInventario('2026', '09', $congregacaoId, 2);
        $this->criaInventario('2026', '10', $congregacaoId, 3);

        $r = Inventario::defineAnoMesUltimoInventariado($congregacaoId);

        $this->assertSame('2026', $r['anoAInventariar']);
        $this->assertSame('11', $r['mesAInventariar']);
    }

    public static function mesesAtuais(): array
    {
        return [
            'jan' => ['2026-01-15', '2026', '01', '2025', '12'],
            'mar' => ['2026-03-15', '2026', '03', '2026', '02'],
            'out' => ['2026-10-15', '2026', '10', '2026', '09'],
            'nov' => ['2026-11-15', '2026', '11', '2026', '10'],
            'dez' => ['2026-12-15', '2026', '12', '2026', '11'],
        ];
    }

    /**
     * @dataProvider mesesAtuais
     */
    public function test_sem_inventario_usa_o_mes_atual_e_o_anterior(string $hoje, string $ano, string $mes, string $anoAnterior, string $mesAnterior): void
    {
        Carbon::setTestNow($hoje);
        $congregacaoId = DB::table('congregacoes')->insertGetId(['nome' => 'Sem Inventário']);

        $r = Inventario::defineAnoMesUltimoInventariado($congregacaoId);

        $this->assertSame($ano, $r['anoAInventariar']);
        $this->assertSame($mes, $r['mesAInventariar']);
        $this->assertSame($anoAnterior, $r['anoAnteriorAInventariar']);
        $this->assertSame($mesAnterior, $r['mesAnteriorAInventariar']);
    }

    private function criaInventario(string $ano, string $mes, ?int $congregacaoId = null, int $publicacaoNumero = 1): int
    {
        $congregacaoId ??= DB::table('congregacoes')->insertGetId(['nome' => 'Congregação Teste']);
        $publicacaoId = DB::table('publicacoes')->insertGetId(['nome' => "Publicação $publicacaoNumero"]);

        Inventario::create([
            'ano' => $ano,
            'mes' => $mes,
            'congregacao_id' => $congregacaoId,
            'publicacao_id' => $publicacaoId,
            'recebido' => 0,
            'estoque' => 10,
            'saida' => 0,
        ]);

        return $congregacaoId;
    }
}
