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
        Schema::create('answer_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('internship_form_id');
            $table->foreign('internship_form_id')->references('id')->on('internship_form')->onDelete('cascade');
            $table->unsignedBigInteger("item_id");
            $table->foreign('item_id')->references('id')->on('form_answer_item')->onDelete('cascade');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_item');
    }
};
