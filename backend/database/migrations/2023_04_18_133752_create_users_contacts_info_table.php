<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users_contacts_info', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('location');
            $table->string('zip');
            $table->foreignUuid('user_id')->references('id')->on('users_currents')->onDelete('cascade');

            $table->commonFields();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_contacts_info');
    }
};
