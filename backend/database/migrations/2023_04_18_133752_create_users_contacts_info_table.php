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

        Schema::create('users_contacts_info', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('location');
            $table->string('zip');
            $table->foreignUuid('user_id')->nullable()->references('id')->on('users_currents')->onDelete('cascade')->default(null);
            $table->foreignUuid('user_credentials_id')->nullable()->references('id')->on('users_credentials_currents')->onDelete('cascade')->default(null);

            $table->foreignUuid('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('users_contacts_info_id')->nullable();
            $table->integer('version')->default(1);
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
