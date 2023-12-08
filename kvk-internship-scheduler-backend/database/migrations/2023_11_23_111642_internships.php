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
        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->float('duration_in_hours')->default(env('INTERNSHIP_DEFAULT_DURATION', 228));
            $table->float('logged_hours')->default(0);
            $table->integer('company_id');
            $table->date('date_from');
            $table->date('date_to');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_mentor_evaluated')->default(false);
            $table->boolean('is_self_evaluated')->default(false);
            $table->boolean('is_head_of_internship_evaluated')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')
                ->on('users')->onDelete('set null');
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
        Schema::dropIfExists('internships');
    }
};
