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
        Schema::create('form_answer_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("question_id");
            $table->foreign('question_id')->references('id')->on('form_question')->onDelete('cascade');
            $table->unsignedBigInteger("answer_id");
            $table->foreign('answer_id')->references('id')->on('form_likert')->onDelete('cascade');
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
        Schema::dropIfExists('form_answer_item');
    }
};
