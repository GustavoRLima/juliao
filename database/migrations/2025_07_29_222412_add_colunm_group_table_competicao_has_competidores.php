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
        Schema::table('competicao_has_competidores', function (Blueprint $table) {
            $table->tinyInteger('grupo')->default(null)->nullable();
            $table->tinyInteger('ordem')->default(null)->nullable();
            $table->tinyInteger('vitorias')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competicao_has_competidores', function (Blueprint $table) {
            $table->dropColumn('grupo');
            $table->dropColumn('ordem');
            $table->dropColumn('vitorias');
        });
    }
};
