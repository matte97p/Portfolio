<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Models\UsersCurrent as User;

class PermissionSeeder extends Seeder
{
    /**
     * Seed Permission
     *
     * @return void
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'Leggere Utenti',
            ],
            [
                'name' => 'Scrivere Utenti',
            ],
            [
                'name' => 'Leggere Ruoli',
            ],
            [
                'name' => 'Scrivere Ruoli',
            ],
            [
                'name' => 'Leggere Permessi',
            ],
            [
                'name' => 'Scrivere Permessi',
            ],
        ];

        foreach($permissions as $permission) {
            Permission::create([
                'name' => $permission["name"],
                'staff_id' => User::findByTaxId("PRNMTT97H28A479G")->id,
            ]);
        }
    }
}
