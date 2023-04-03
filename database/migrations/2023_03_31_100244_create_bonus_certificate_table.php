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
        Schema::create('bonus_certificate_data', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("linkedin_review");
            $table->string('course_star_review');
            $table->text('course_feedback');
            $table->text('internal_feedback');
            $table->integer('user_id');
            $table->integer('course_id');
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
        Schema::dropIfExists('bonus_certificate_data');
    }
};
