<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
return new class  extends Migration
{
    public function up(): void
    {

        Schema::create(config('wpwhales.subscriptions.tables.feature_plan'), function (Blueprint $table) {
            $table->unsignedInteger('plan_id');
            $table->unsignedInteger('feature_id');
            $table->string('value');
            $table->unsignedSmallInteger('resettable_period')->default(0);
            $table->string('resettable_interval')->default('month');

            $table->foreign('plan_id')
                ->references('id')
                ->on(config('wpwhales.subscriptions.tables.plans'))
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('feature_id')
                ->references('id')
                ->on(config('wpwhales.subscriptions.tables.features'))
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->primary(['plan_id', 'feature_id']);
            $table->unique(['plan_id', 'feature_id']);


        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('wpwhales.subscriptions.tables.feature_plan'));
    }
};
