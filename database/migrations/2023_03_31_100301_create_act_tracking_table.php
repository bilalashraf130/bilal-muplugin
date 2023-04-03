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
        Schema::create('act_tracking', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("event_name")->index("event_name");
            $table->string('user_email')->index("user_email");
            $table->string('first_name')->index("first_name");
            $table->string('last_name')->index("last_name");
            $table->string('api_error_message')->index("api_error_message")->nullable();
            $table->string('message')->index("message");
            $table->boolean('status');
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
        Schema::dropIfExists('act_tracking');
    }
};
