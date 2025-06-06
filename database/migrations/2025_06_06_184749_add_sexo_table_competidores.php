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
        Schema::table('competidores', function (Blueprint $table) {
            $table->tinyInteger('sexo')->nullable()->default(0)->comment('1 - masculino, 2 - feminino');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competidores', function (Blueprint $table) {
            //
        });
    }
};
