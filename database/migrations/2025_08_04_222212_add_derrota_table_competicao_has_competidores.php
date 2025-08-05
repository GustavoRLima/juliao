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
            $table->tinyInteger('derrota')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competicao_has_competidores', function (Blueprint $table) {
            $table->dropColumn('derrota');
        });
    }
};
