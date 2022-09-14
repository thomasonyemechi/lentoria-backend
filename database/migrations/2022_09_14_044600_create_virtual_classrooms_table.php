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
        Schema::create('virtual_classrooms', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->integer('section_id');
            $table->integer('lecture_id');
            $table->integer('user_id');
            $table->longText('comment');
            $table->integer('status')->default(1);
            $table->string('content');
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
        Schema::dropIfExists('virtual_classrooms');
    }
};
