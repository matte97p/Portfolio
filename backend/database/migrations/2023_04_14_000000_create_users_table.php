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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('surname');
            $table->string('taxid')->unique(); // fiscal code
            $table->enum('gender', ['m', 'f']);
            $table->date('birth_date');

            $table->uuid('staff_id')->nullable(); //$table->foreignUuid('staff_id')->nullable()->references('id')->on('users_currents')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('users_id')->nullable();
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
        Schema::dropIfExists('users');
    }
};
