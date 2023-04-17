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
            $table->foreign('users_id')->nullable()->references('id')->on('users_currents')->onDelete('cascade')->default(null);
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->foreign('users_id')->nullable()->references('id')->on('users_currents')->onDelete('cascade')->default(null);
            $table->foreign('id')->nullable()->references('id')->on('permissions_currents')->onDelete('cascade')->default(null);
        });

        /* HISTORY */
        DB::unprepared("CREATE TABLE IF NOT EXISTS permissions_history () INHERITS (permissions);");
        Schema::table('permissions_history', function (Blueprint $table) {
            $table->primary('id');
            $table->foreign('users_id')->nullable()->references('id')->on('users_currents')->onDelete('cascade')->default(null);
            $table->foreign('id')->nullable()->references('id')->on('permissions_currents')->onDelete('cascade')->default(null);
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
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['id']);
        });
        Schema::dropIfExists('permissions_currents');

        /* HISTORY */
        Schema::table('permissions_history', function (Blueprint $table) {
            $table->dropForeign(['id']);
        });
        Schema::dropIfExists('permissions_history');
    }
};
