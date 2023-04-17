<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use Faker\Provider\it_IT\Person;
use App\Http\Controllers\Concrete\RoleController;
use App\Http\Controllers\Concrete\PermissionController;

class GiveRoleSeeder extends Seeder
{
    /**
     * Create 2 fake data for User::class
     *
     * @return void
     */
    public function run(): void
    {
        $master = User::findByTaxId('PRNMTT97H28A479G')->id;

        $roleController = new RoleController(new Request());
        $roleList = array_values(RoleController::list());
        $permissionList = array_values(PermissionController::list());

        /* Master */
            $giveRoleRequest = new Request(['users' => [$master], 'roles' => ['Super Admin']]);
            $roleController->give($giveRoleRequest);

            $givePermissionRequest = new Request(['role' => 'Super Admin', 'permissions' => $permissionList]);
            $roleController->givePermission($givePermissionRequest);
        /* Master */

        foreach(User::where('id', '!=', $master)->get() as $user)
        {
            $request = new Request( [ 'users' => [$user->id], 'roles' => [ $roleList[array_rand($roleList)] ] ] );
            $roleController->give($request);
        }

        foreach($roleList as $role)
        {
            $request = new Request( [ 'role' => $role, 'permissions' => [ $permissionList[array_rand($permissionList)] ] ] );
            $roleController->givePermission($request);
        }

        $this->command->info( 'che bello!' );
    }
}
