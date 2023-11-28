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
        Schema::create('learning_material_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('learning_material_id');
            $table->morphs('accessable');
            $table->foreign('learning_material_id')->references('id')->on('learning_materials')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learning_material_access');
    }
};
