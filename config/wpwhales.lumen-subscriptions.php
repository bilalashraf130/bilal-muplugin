<?php

declare(strict_types=1);

return [

    // Manage autoload migrations
    'autoload_migrations' => true,

    // Subscriptions Database Tables
    'tables' => [

        'plans' => 'plans',
        'features' => 'features',
        'feature_plan' => 'feature_plan',
        'plan_subscriptions' => 'plan_subscriptions',
        'plan_subscription_usage' => 'plan_subscription_usage',

    ],

    // Subscriptions Models
    'models' => [

        'plan' => \WPWhales\Subscriptions\Models\Plan::class,
        'feature' => \WPWhales\Subscriptions\Models\Feature::class,
        'plan_subscription' => \WPWhales\Subscriptions\Models\PlanSubscription::class,
        'plan_subscription_usage' => \WPWhales\Subscriptions\Models\PlanSubscriptionUsage::class,

    ],

];
