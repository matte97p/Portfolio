<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\UsersCredentialsCurrents;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserCredentials extends Seeder
{
    /**
     * Seed UsersCredentials
     *
     * @return void
     */
    public function run()
    {
        $master = User::findByTaxId('PRNMTT97H28A479G')->id;

        $faker = Factory::create();

        UsersCredentialsCurrents::create([
            'user_id' => $master,
            'username' => 'prova@example.net',
            'password' => bcrypt("$2y$10!aS"),
            'remember_token' => Str::random(10),
            'staff_id' => $master,
        ]);

        foreach(User::where('id', '!=', $master)->get() as $user)
        {
            UsersCredentialsCurrents::create([
                'user_id' => $user->id,
                'username' => $faker->username(),
                'password' => bcrypt("$2y$10!aS"),
                'remember_token' => Str::random(10),
                'staff_id' => $master,
            ]);
        }
    }
}
