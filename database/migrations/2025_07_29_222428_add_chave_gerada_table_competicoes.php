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
        Schema::table('competicoes', function (Blueprint $table) {
            $table->tinyInteger('chave_gerada')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competicoes', function (Blueprint $table) {
            $table->dropColumn('chave_gerada');
        });
    }
};
