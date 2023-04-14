<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterUserSeeder extends Seeder
{
    /**
     * Create master user for User::class
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                'name' => 'Matteo',
                'surname' => 'Perino',
                'taxid' => 'PRNMTT97H28A479G',
                'email' => 'prova@example.net',
                'email_verified_at' => now(),
                'password' => bcrypt("$2y$10$"),
                'remember_token' => Str::random(10),
            ]
        );
    }
}
