<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Atribui todos os dados existentes à congregação "Novo Méier"
     * para garantir compatibilidade com o sistema já em uso.
     */
    public function up(): void
    {
        // 1. Garantir que congregação "Novo Méier" existe com ID = 1
        DB::table('congregacoes')->insertOrIgnore([
            'id' => 1,
            'nome' => 'Novo Méier',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. Atribuir TODOS os usuários existentes a "Novo Méier"
        DB::table('users')
            ->whereNull('congregacao_id')
            ->update(['congregacao_id' => 1]);

        // 3. Atribuir TODAS as pessoas existentes a "Novo Méier"
        DB::table('pessoas')
            ->whereNull('congregacao_id')
            ->update(['congregacao_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter associações apenas (não deletar congregação)
        DB::table('users')
            ->where('congregacao_id', 1)
            ->update(['congregacao_id' => null]);

        DB::table('pessoas')
            ->where('congregacao_id', 1)
            ->update(['congregacao_id' => null]);
    }
};
