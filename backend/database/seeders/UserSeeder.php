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
     * Create some fake User and Master
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Factory::create();

        User::create([
            'name' => 'Matteo',
            'surname' => 'Perino',
            'taxid' => 'PRNMTT97H28A479G',
            'gender' => $faker->randomElement(['m', 'f']),
            'birth_date' => $faker->date('Y_m_d'),
        ]);

        for ($i = 0; $i < 4; $i++) {
            User::create([
                'name' => $faker->firstName(),
                'surname' => $faker->lastName(),
                'taxid' => Person::taxId(),
                'gender' => $faker->randomElement(['m', 'f']),
                'birth_date' => $faker->unique()->dateTimeBetween('-70 years', '-19 years')->format('Y-m-d'),
                'staff_id' => User::findByTaxId('PRNMTT97H28A479G')->id,
            ]);
        }
    }
}
