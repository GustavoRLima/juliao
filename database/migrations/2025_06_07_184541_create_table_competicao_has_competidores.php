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
        Schema::create('competicao_has_competidores', function (Blueprint $table) {
            $table->foreignId('competicao_id')->references('id')->on('competicoes');
            $table->foreignId('competidor_id')->references('id')->on('competidores');
            $table->string('categoria_competicao')->index('categoria_competicao_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competicao_has_competidores');
    }
};
