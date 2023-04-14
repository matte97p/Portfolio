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
        DB::unprepared(DB::raw("CREATE TABLE IF NOT EXISTS roles_currents () INHERITS (roles);"));
        Schema::table('roles_currents', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('users_id')->nullable()->references('id')->on('users_currents')->onDelete('cascade')->default(null);
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->foreign('users_id')->nullable()->references('id')->on('users_currents')->onDelete('cascade')->default(null);
            $table->foreign('id')->nullable()->references('id')->on('roles_currents')->onDelete('cascade')->default(null);
        });

        /* HISTORY */
        DB::unprepared(DB::raw("CREATE TABLE IF NOT EXISTS roles_history () INHERITS (roles);"));
        Schema::table('roles_history', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('users_id')->nullable()->references('id')->on('users_currents')->onDelete('cascade')->default(null);
            $table->foreign('id')->nullable()->references('id')->on('roles_currents')->onDelete('cascade')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* CURRENTS */
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['id']);
        });
        Schema::dropIfExists('roles_currents');

        /* HISTORY */
        Schema::table('roles_history', function (Blueprint $table) {
            $table->dropForeign(['id']);
        });
        Schema::dropIfExists('roles_history');
    }
};
