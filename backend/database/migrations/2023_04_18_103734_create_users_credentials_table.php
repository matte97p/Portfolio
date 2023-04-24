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
        Schema::create('users_credentials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->references('id')->on('users_currents')->onDelete('cascade');
            $table->string('username')->unique();
            $table->string('password');
            $table->rememberToken();

            $table->foreignUuid('staff_id')->references('id')->on('users_currents')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('users_credentials_id')->nullable();
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
        Schema::dropIfExists('users_credentials');
    }
};
