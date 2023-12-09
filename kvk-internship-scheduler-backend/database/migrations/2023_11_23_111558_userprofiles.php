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
        Schema::create('userprofiles', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('company_id')->nullable();
            $table->string('student_group_id')->nullable();
            $table->integer('role_id')->default(0);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('fullname');
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('image_path')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userprofiles');
    }
};
