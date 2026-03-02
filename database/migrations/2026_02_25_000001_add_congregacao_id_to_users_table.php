<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Novo usuário NÃO tem congregação até Ancião/Admin atribuir
            $table->unsignedBigInteger('congregacao_id')->nullable();
            
            // Chave estrangeira
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['congregacao_id']);
            $table->dropColumn('congregacao_id');
        });
    }
};
