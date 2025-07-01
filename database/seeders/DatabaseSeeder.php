<?php

namespace Database\Seeders;

use App\Models\CompetidorModel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        if(count(User::get()) == 0){
            User::insert([
                'name' => 'Automatico sistema',
                'email' => 'automaticosistema@example.com',
                'password' => 12345678,
            ]);
        }
        
        if(count(CompetidorModel::get()) == 0){
            $this->call([
                CompetidoresSeed::class,
                CategoriaSeed::class,
            ]);
        }

        
    }
}
