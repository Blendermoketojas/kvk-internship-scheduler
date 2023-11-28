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
        Schema::create('template_likert', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("template_id");
            $table->foreign('template_id')->references('id')->on('form_template')->onDelete('cascade');
            $table->unsignedBigInteger("likert_id");
            $table->foreign('likert_id')->references('id')->on('form_likert')->onDelete('cascade');
            $table->integer("sequence");
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
        Schema::dropIfExists('template_likert');
    }
};
