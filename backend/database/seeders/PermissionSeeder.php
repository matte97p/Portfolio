<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Create for Permission
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
                'guard_name' => 'api',
            ]);
        }
    }
}
