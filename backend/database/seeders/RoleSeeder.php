<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Create for role
     *
     * @return void
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
            ],
            [
                'name' => 'Direttore Unità Operativa',
            ],
            [
                'name' => 'Direttore Scuola',
            ],
            [
                'name' => 'Vice Direttore',
            ],
            [
                'name' => 'Docente Coordinatore',
            ],
            [
                'name' => 'Docente',
            ],
            [
                'name' => 'Tutor',
            ],
            [
                'name' => 'Segreteria Ateneo',
            ],
            [
                'name' => 'Segreteria Scuola',
            ],
            [
                'name' => 'Segreteria Didattica Ateneo',
            ],
            [
                'name' => 'Segreteria Carriera Ateneo',
            ],
            [
                'name' => 'Segreteria Tirocinio',
            ],
            [
                'name' => 'Specializzando',
            ],
            [
                'name' => 'Studente',
            ],
        ];

        foreach($roles as $role) {
            Role::create([
                'name' => $role["name"],
                'guard_name' => 'api',
                'users_id' => User::findByTaxId("PRNMTT97H28A479G")->id,
            ]);
        }
    }
}
