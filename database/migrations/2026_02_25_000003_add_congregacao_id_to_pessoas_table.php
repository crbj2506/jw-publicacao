<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Pessoas herdam a congregacao_id do User (Servo/Ancião) que as cria.
     * Por enquanto será nullable para manter compatibilidade com dados antigos.
     */
    public function up(): void
    {
        Schema::table('pessoas', function (Blueprint $table) {
            // Pessoa pertence a uma congregação
            // Será preenchida automaticamente no Controller com a congregação do User logado
            $table->unsignedBigInteger('congregacao_id')->nullable();
            
            $table->foreign('congregacao_id')
                ->references('id')
                ->on('congregacoes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->dropForeign(['congregacao_id']);
            $table->dropColumn('congregacao_id');
        });
    }
};
