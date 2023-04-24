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
        DB::unprepared("CREATE TABLE IF NOT EXISTS users_credentials_currents () INHERITS (users_credentials);");
        Schema::table('users_credentials_currents', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('users_credentials_id')->references('id')->on('users_credentials_currents')->onDelete('cascade')->nullable();

            $table->foreign('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users_currents')->onDelete('cascade');
        });

        /* HISTORY */
        DB::unprepared("CREATE TABLE IF NOT EXISTS users_credentials_history () INHERITS (users_credentials);");
        Schema::table('users_credentials_history', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('users_credentials_id')->references('id')->on('users_credentials_currents')->onDelete('cascade')->nullable();

            $table->foreign('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users_currents')->onDelete('cascade');
        });

        /* GENERIC */
        Schema::table('users_credentials', function (Blueprint $table) {
            $table->foreign('users_credentials_id')->references('id')->on('users_credentials_currents')->onDelete('cascade')->nullable();
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
        Schema::table('users_credentials', function (Blueprint $table) {
            $table->dropForeign(['users_credentials_id']);
        });

        /* HISTORY */
        Schema::dropIfExists('users_credentials_history');

        /* CURRENTS */
        Schema::dropIfExists('users_credentials_currents');
    }
};
