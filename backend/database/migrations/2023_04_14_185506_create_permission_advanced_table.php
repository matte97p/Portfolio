<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar;

class CreatePermissionAdvancedTable extends Migration
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

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
            $table->foreignUuid(PermissionRegistrar::$pivotPermission)->references('id')->on($tableNames['permissions'].'_currents')->onDelete('cascade');

            $table->string('model_type');
            $table->uuid($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreignUuid('staff_id')->nullable()->references('id')->on('users_currents')->onDelete('cascade');
            // $table->timestamps();
            // $table->softDeletes();
            // table_names_id
            // $table->integer('version')->default(1);

            $table->primary([PermissionRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_permission_model_type_primary');

        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $teams) {
            $table->foreignUuid(PermissionRegistrar::$pivotRole)->references('id')->on($tableNames['roles'].'_currents')->onDelete('cascade');

            $table->string('model_type');
            $table->uuid($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreignUuid('staff_id')->nullable()->references('id')->on('users_currents')->onDelete('cascade');
            // $table->timestamps();
            // $table->softDeletes();
            // table_names_id
            // $table->integer('version')->default(1);


            $table->primary([PermissionRegistrar::$pivotRole, $columnNames['model_morph_key'], 'model_type'], 'model_has_roles_role_model_type_primary');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->foreignUuid(PermissionRegistrar::$pivotPermission)->references('id')->on($tableNames['permissions'].'_currents')->onDelete('cascade');
            $table->foreignUuid(PermissionRegistrar::$pivotRole)->references('id')->on($tableNames['roles'].'_currents')->onDelete('cascade');

            $table->foreignUuid('staff_id')->nullable()->references('id')->on('users_currents')->onDelete('cascade');
            // $table->timestamps();
            // $table->softDeletes();
            // table_names_id
            // $table->integer('version')->default(1);

            $table->primary([PermissionRegistrar::$pivotPermission, PermissionRegistrar::$pivotRole], 'role_has_permissions_permission_id_role_id_primary');
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

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
    }
}
