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
        DB::unprepared("CREATE TABLE IF NOT EXISTS permissions_currents () INHERITS (permissions);");
        Schema::table('permissions_currents', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('permissions_id')->references('id')->on('permissions_currents')->onDelete('cascade')->nullable();

            $table->foreign('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
        });

        /* HISTORY */
        DB::unprepared("CREATE TABLE IF NOT EXISTS permissions_history () INHERITS (permissions);");
        Schema::table('permissions_history', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('permissions_id')->references('id')->on('permissions_currents')->onDelete('cascade')->nullable();

            $table->foreign('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
        });

        /* GENERIC */
        Schema::table('permissions', function (Blueprint $table) {
            $table->foreign('permissions_id')->references('id')->on('permissions_currents')->onDelete('cascade')->nullable();
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
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['permissions_id']);
        });

        /* HISTORY */
        Schema::dropIfExists('permissions_history');

        /* CURRENTS */
        Schema::dropIfExists('permissions_currents');
    }
};
