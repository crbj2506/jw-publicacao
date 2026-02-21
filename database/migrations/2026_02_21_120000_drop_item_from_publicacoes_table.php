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
        if (Schema::hasTable('publicacoes') && Schema::hasColumn('publicacoes', 'item')) {
            Schema::table('publicacoes', function (Blueprint $table) {
                $table->dropColumn('item');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('publicacoes') && !Schema::hasColumn('publicacoes', 'item')) {
            Schema::table('publicacoes', function (Blueprint $table) {
                $table->string('item', 20)->nullable();
            });
        }
    }
};
