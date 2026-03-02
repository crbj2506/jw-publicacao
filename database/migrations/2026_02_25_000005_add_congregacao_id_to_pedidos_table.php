<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->unsignedBigInteger('congregacao_id')->nullable()->after('publicacao_id');
            $table->foreign('congregacao_id')->references('id')->on('congregacoes')->onDelete('cascade');
        });

        // Preenche congregacao_id a partir da pessoa associada
        DB::table('pedidos')
            ->join('pessoas', 'pedidos.pessoa_id', '=', 'pessoas.id')
            ->update(['pedidos.congregacao_id' => DB::raw('pessoas.congregacao_id')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['congregacao_id']);
            $table->dropColumn('congregacao_id');
        });
    }
};
