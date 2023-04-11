<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Create 33 fake data for Product::class
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 33; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => '$2y$10$', // password
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
