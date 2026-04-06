<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gym;

class GymSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gym::create([
            'name' => 'Vault Gym Dublin',
            'location' => 'Dublin City Centre',
        ]);

        Gym::create([
            'name' => 'Vault Gym Cork',
            'location' => 'Cork City',
        ]);

        Gym::create([
            'name' => 'Vault Gym Galway',
            'location' => 'Galway City',
        ]);
    }
}