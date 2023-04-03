<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

if(getenv("LANDO")){
    $router->get("test",[
        function(){




            $user = \App\Models\User::find(1);
            //$user->planSubscription("main-1")->recordFeatureUsage("listings");

            dd($user->planSubscription("main")->getFeatureUsage("listings"));
            $plan = app('wpwhales.subscriptions.plan')->find(12);

            $user->newPlanSubscription('main', $plan);
        dd($user);
      dd(123);

        }
    ]);
}

$router->get("force_password_change",[
    "middleware"=>["auth"],
    "as"=>"forgot_password_change",
    "uses"=>"UserController@show_force_password_reset"
]);
$router->post("force_password_change",[
    "middleware"=>["auth"],
    "uses"=>"UserController@force_password_reset"
]);


$router->get("thank-you",[
    "wordpress",
    "as"=>"course_thank_you",
    "middleware"=>["auth","signed","throttle:5,1"],
    "uses"=>"LearnDash@redirect_to_thankyou_page"
]);
