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
        Schema::create('grade_item', function (Blueprint $table) {
            $table->id();
            $table->string('comment');
            $table->float('grade');
            $table->date('date');
            $table->unsignedBigInteger('internship_id');
            $table->foreign('internship_id')->references('id')->on('internships')->onDelete('cascade');
            $table->boolean('is_final')->default(false);
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
        Schema::dropIfExists('grade_item');
    }
};
