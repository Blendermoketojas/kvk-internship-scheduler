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
        Schema::create('internship_form', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("template_id")->nullable();
            $table->foreign('template_id')->references('id')->on('form_template')->onDelete('set null');
            $table->unsignedBigInteger("internship_id")->nullable();
            $table->foreign('internship_id')->references('id')->on('internships')->onDelete('set null');
            $table->integer("sequence");
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('internship_form');
    }
};
