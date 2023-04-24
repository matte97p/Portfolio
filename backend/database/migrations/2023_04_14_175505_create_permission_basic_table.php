<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

class CreatePermissionBasicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }
        if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
            throw new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->uuid('id')->primary(); // permission id
            $table->string('name');
            $table->string('guard_name');

            $table->foreignUuid('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('permissions_id')->nullable();
            $table->integer('version')->default(1);

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->uuid('id')->primary(); // role id
            $table->string('name');
            $table->string('guard_name');

            $table->foreignUuid('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('roles_id')->nullable()->nullable();
            $table->integer('version')->default(1);

            $table->unique(['name', 'guard_name']);
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::drop($tableNames['permissions']);
        Schema::drop($tableNames['roles']);
    }
}
