<?php
/*
 * Add the name of the hooks and attach all associated classes in it
 *
 */
return [

    "init"=>[
        App\Hooks\ForceResetPassword::class,
        App\Hooks\LearnDash::class,
        App\Hooks\User::class,
        App\Hooks\Woocommerce::class,
        App\Hooks\CourseReviews::class,
//        App\Helpers\ACTTrackingHelper::class,
        App\Hooks\ProductPlan::class,
    ],
    "admin_init"=>[
        App\Hooks\AdminSettings::class,

    ],



];
