<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* if not exsist master user create it */
        if(User::where('taxid', 'PRNMTT97H28A479G')->get()->count() == 0)
        {
            $this->call([
                MasterUserSeeder::class,
            ]);
        }

        User::factory(2)->create();

        $this->call([
            UserSeeder::class,
        ]);
    }
}
