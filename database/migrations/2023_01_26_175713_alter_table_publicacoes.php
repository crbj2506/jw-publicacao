<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        //
        Schema::table('publicacoes', function (Blueprint $table) {
            $table->float('proporcao_cm', 2, 1)->after('item')->default(0);
            $table->integer('proporcao_unidade')->after('item')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('publicacoes', function (Blueprint $table) {
            $table->dropColumn('proporcao_unidade');
            $table->dropColumn('proporcao_cm');
        });
    }
};
