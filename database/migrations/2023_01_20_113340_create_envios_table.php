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
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nota')->unique();
            $table->date('data')->nullable()->default(null);
            $table->date('retirada')->nullable()->default(null);
            $table->unsignedBigInteger('congregacao_id');
            $table->timestamps();
            $table->foreign('congregacao_id')->references('id')->on('congregacoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('envios');
    }
};
