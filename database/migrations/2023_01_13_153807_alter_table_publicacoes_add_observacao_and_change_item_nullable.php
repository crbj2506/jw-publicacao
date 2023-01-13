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
        Schema::table('publicacoes', function (Blueprint $table) {
            $table->string('item', 20)->nullable()->change();
            $table->string('observacao', 200)->nullable()->after('nome');
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
            $table->string('item', 20)->change();
            $table->dropColumn('observacao');
        });
    }
};
