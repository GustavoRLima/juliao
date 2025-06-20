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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('nome');
            $table->integer('idade_inicio')->index('idade_inicio_index');
            $table->integer('idade_fim')->index('idade_fim_index');
            $table->decimal('peso_inicio', 6,3)->index('peso_inicio_index');
            $table->decimal('peso_fim', 6,3)->index('peso_fim_index');
            $table->integer('sexo')->comment('1 - ambos, 2 - masculino, 3 - feminino')->index('sexo_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
