<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('evento'); // login, logout, created, updated, deleted
            $table->string('auditable_type')->nullable(); // Model afetado
            $table->unsignedBigInteger('auditable_id')->nullable(); // ID do registro
            $table->json('valores_antigos')->nullable();
            $table->json('valores_novos')->nullable();
            $table->string('url')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auditorias');
    }
};