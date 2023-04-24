<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    const table_name = 'users';
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
        });

        /* HISTORY */
        Schema::table(self::table_name.'_history', function (Blueprint $table) {
            $table->inherits(self::table_name);
            $table->foreignCurrent(self::table_name);
        });

        /* GENERIC */ //aggiungo staff_id foreign perchè non esiste la tabella in origine
        Schema::table(self::table_name, function (Blueprint $table) {
            $table->foreignCurrent(self::table_name);
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
        Schema::table(self::table_name, function (Blueprint $table) {
            $table->dropForeign([self::table_name.'_id']);
            $table->dropForeign(['staff_id']);
        });

        /* HISTORY */
        Schema::dropIfExists(self::table_name.'_history');

        /* CURRENTS */
        Schema::dropIfExists(self::table_name.'_currents');
    }
};
