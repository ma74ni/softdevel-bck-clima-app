<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i=0; $i < 20; $i++) {
            User::create([
                'name' => $faker->name,
                'latitude' => $faker->latitude(-90, 90),
                'longitude' => $faker->longitude(-180, 180),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
