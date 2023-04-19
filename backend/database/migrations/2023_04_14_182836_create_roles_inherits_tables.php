<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* CURRENTS */
        DB::unprepared("CREATE TABLE IF NOT EXISTS roles_currents () INHERITS (roles);");
        Schema::table('roles_currents', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('roles_id')->references('id')->on('roles_currents')->onDelete('cascade')->nullable();

            $table->foreign('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
        });

        /* HISTORY */
        DB::unprepared("CREATE TABLE IF NOT EXISTS roles_history () INHERITS (roles);");
        Schema::table('roles_history', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('roles_id')->references('id')->on('roles_currents')->onDelete('cascade')->nullable();

            $table->foreign('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
        });

        /* GENERIC */
        Schema::table('roles', function (Blueprint $table) {
            $table->foreign('roles_id')->references('id')->on('roles_currents')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* GENERIC */
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['roles_id']);
        });

        /* HISTORY */
        Schema::dropIfExists('roles_history');

        /* CURRENTS */
        Schema::dropIfExists('roles_currents');
    }
};
