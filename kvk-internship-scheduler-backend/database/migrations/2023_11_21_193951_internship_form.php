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
            $table->integer("form_id");
            $table->integer("form_answer_id")->nullable();
            $table->integer("internship_id");
            $table->integer("sequence");
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
