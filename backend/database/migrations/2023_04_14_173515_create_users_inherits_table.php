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
        DB::unprepared("CREATE TABLE IF NOT EXISTS users_currents () INHERITS (users);");
        Schema::table('users_currents', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('users_id')->references('id')->on('users_currents')->onDelete('cascade')->default(null);

            $table->foreign('staff_id')->references('id')->on('users_currents')->onDelete('cascade')->default(null);
        });

        /* HISTORY */
        DB::unprepared("CREATE TABLE IF NOT EXISTS users_history () INHERITS (users);");
        Schema::table('users_history', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('users_id')->references('id')->on('users_currents')->onDelete('cascade')->default(null);

            $table->foreign('staff_id')->references('id')->on('users_currents')->onDelete('cascade')->default(null);
        });

        /* GENERIC */ //aggiungo staff_id foreign perchè non esiste la tabella in origine
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('users_id')->references('id')->on('users_currents')->onDelete('cascade')->default(null);

            $table->foreign('staff_id')->references('id')->on('users_currents')->onDelete('cascade')->default(null);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['users_id']);
            $table->dropForeign(['staff_id']);
        });

        /* HISTORY */
        Schema::dropIfExists('users_history');

        /* CURRENTS */
        Schema::dropIfExists('users_currents');
    }
};
