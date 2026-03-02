<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('publicacoes', function (Blueprint $table) {
            $table->unsignedBigInteger('congregacao_id')->nullable()->after('codigo');
            $table->foreign('congregacao_id')->references('id')->on('congregacoes')->onDelete('cascade');
        });

        // Atribui publicações existentes à congregação 1 (padrão)
        DB::table('publicacoes')->update(['congregacao_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publicacoes', function (Blueprint $table) {
            $table->dropForeign(['congregacao_id']);
            $table->dropColumn('congregacao_id');
        });
    }
};
