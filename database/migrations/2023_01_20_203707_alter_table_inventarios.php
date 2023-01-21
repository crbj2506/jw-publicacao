<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('inventarios', function (Blueprint $table) {
            $table->dropColumn('quantidade');
            $table->dropColumn('local');
            $table->integer('saida')->after('publicacao_id')->default(0);
            $table->integer('estoque')->after('publicacao_id')->default(0);
            $table->integer('recebido')->after('publicacao_id')->default(0);
            $table->string('mes', 2)->after('id');
            $table->string('ano', 4)->after('id');
            $table->unsignedBigInteger('publicacao_id')->nullable()->change();
        });
        Schema::table('envios', function (Blueprint $table) {
            $table->boolean('inventariado')->after('retirada')->default(false);
        });
        Schema::table('estoques', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable()->default(null)->change();
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
        Schema::table('estoques', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->change();
        });
        Schema::table('envios', function (Blueprint $table) {
            $table->dropColumn('inventariado');
        });
        Schema::table('inventarios', function (Blueprint $table) {
            $table->unsignedBigInteger('publicacao_id')->change();
            $table->dropColumn('saida');
            $table->dropColumn('estoque');
            $table->dropColumn('recebido');
            $table->dropColumn('mes');
            $table->dropColumn('ano');
            $table->integer('quantidade')->default(0)->after('publicacao_id');
            $table->string('local')->after('publicacao_id');
        });
    }
};
