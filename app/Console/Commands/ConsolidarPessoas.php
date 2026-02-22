<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pessoa;
use App\Models\Pedido;

class ConsolidarPessoas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pessoas:consolidar {nome_original} {nome_copia}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Consolida registros duplicados de pessoa: transfere pedidos da cópia para o original e deleta a cópia';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $nomeOriginal = $this->argument('nome_original');
        $nomeCopia = $this->argument('nome_copia');

        // Encontrar as pessoas
        $pessoaOriginal = Pessoa::where('nome', $nomeOriginal)->first();
        $pessoaCopia = Pessoa::where('nome', $nomeCopia)->first();

        // Validações
        if (!$pessoaOriginal) {
            $this->error("Pessoa original '{$nomeOriginal}' não encontrada.");
            return Command::FAILURE;
        }

        if (!$pessoaCopia) {
            $this->error("Pessoa cópia '{$nomeCopia}' não encontrada.");
            return Command::FAILURE;
        }

        if ($pessoaOriginal->id === $pessoaCopia->id) {
            $this->error("Os nomes informados referem-se à mesma pessoa.");
            return Command::FAILURE;
        }

        // Exibir informações
        $this->info("Pessoa original: {$pessoaOriginal->nome} (ID: {$pessoaOriginal->id})");
        $this->info("Pessoa cópia: {$pessoaCopia->nome} (ID: {$pessoaCopia->id})");

        // Contar pedidos
        $pedidosCopia = Pedido::where('pessoa_id', $pessoaCopia->id)->count();
        $this->info("Total de pedidos a transferir: {$pedidosCopia}");

        // Confirmação
        if (!$this->confirm("Deseja prosseguir com a consolidação?")) {
            $this->info("Operação cancelada.");
            return Command::SUCCESS;
        }

        try {
            // 1. Transferir pedidos de Paula Marques para Paula
            Pedido::where('pessoa_id', $pessoaCopia->id)
                ->update(['pessoa_id' => $pessoaOriginal->id]);

            $this->info("✓ Pedidos transferidos: {$pedidosCopia}");

            // 2. Deletar a pessoa cópia (soft delete)
            $pessoaCopia->delete();
            $this->info("✓ Pessoa cópia '{$nomeCopia}' deletada com soft delete");

            // 3. Renomear a pessoa original
            $pessoaOriginal->update(['nome' => $nomeCopia]);
            $this->info("✓ Pessoa original renomeada para '{$nomeCopia}'");

            $this->info("\n✅ Consolidação concluída com sucesso!");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Erro ao consolidar: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
