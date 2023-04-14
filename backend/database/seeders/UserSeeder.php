<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Faker\Provider\it_IT\Person;

class UserSeeder extends Seeder
{
    /**
     * Create 2 fake data for User::class
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 2; $i++) {
            User::create([
                'name' => $faker->firstName(),
                'surname' => $faker->lastName(),
                'taxid' => Person::taxId(),
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => bcrypt("$2y$10$"),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
