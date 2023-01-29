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
            $table->float('proporcao_cm', 2, 2)->after('item')->default(0)->change();
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
            $table->float('proporcao_cm', 2, 1)->after('item')->default(0)->change();
        });
    }
};
