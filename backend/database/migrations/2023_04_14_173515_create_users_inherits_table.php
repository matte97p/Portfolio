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
        DB::unprepared(DB::raw("CREATE TABLE IF NOT EXISTS users_currents () INHERITS (users);"));
        Schema::table('users_currents', function (Blueprint $table) {
            $table->primary('id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id')->nullable()->references('id')->on('users_currents')->onDelete('cascade')->default(null);
        });

        /* HISTORY */
        DB::unprepared(DB::raw("CREATE TABLE IF NOT EXISTS users_history () INHERITS (users);"));
        Schema::table('users_history', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('id')->nullable()->references('id')->on('users_currents')->onDelete('cascade')->default(null);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id']);
        });
        Schema::dropIfExists('users_currents');

        /* HISTORY */
        Schema::table('users_history', function (Blueprint $table) {
            $table->dropForeign(['id']);
        });
        Schema::dropIfExists('users_history');
    }
};
