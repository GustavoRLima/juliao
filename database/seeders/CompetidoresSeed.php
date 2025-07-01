<?php

namespace Database\Seeders;

use App\Models\CompetidorModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompetidoresSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompetidorModel::factory()->count(50)->create();
    }
}
