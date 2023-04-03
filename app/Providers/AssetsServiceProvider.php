<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AssetsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if(function_exists("add_action")){

            add_action("wp_enqueue_scripts",[$this,"enqueueFrontendAssets"]);
        }
    }


    public function enqueueFrontendAssets(){
        if (is_checkout()){
            wp_enqueue_script(
                "custom-js",
                asset("/misc/custom_js.js"),
                ["jquery"],
                false,
                true
            );
        }
    }
}
