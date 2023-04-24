<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    const table_name = 'users_credentials';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* CURRENTS */
        Schema::table(self::table_name.'_currents', function (Blueprint $table) {
            $table->inherits(self::table_name);

            $table->foreign('user_id')->references('id')->on('users_currents')->onDelete('cascade');
        });

        /* HISTORY */
        Schema::table(self::table_name.'_history', function (Blueprint $table) {
            $table->inherits(self::table_name);
            $table->foreignCurrent(self::table_name);

            $table->foreign('user_id')->references('id')->on('users_currents')->onDelete('cascade');
        });

        /* GENERIC */
        Schema::table(self::table_name, function (Blueprint $table) {
            $table->foreignCurrent(self::table_name);
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
        Schema::table(self::table_name, function (Blueprint $table) {
            $table->dropForeign([self::table_name.'_id']);
        });

        /* HISTORY */
        Schema::dropIfExists(self::table_name.'_history');

        /* CURRENTS */
        Schema::dropIfExists(self::table_name.'_currents');
    }
};
