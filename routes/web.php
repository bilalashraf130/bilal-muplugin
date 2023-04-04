<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use App\Http\Controllers\FeatureController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use WPWhales\Subscriptions\Models\PlanSubscription;
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
           $user = Auth::user();

            $user1 = User::find($user->ID);
            $get_user_membership_type = app('rinvex.subscriptions.plan_subscription')->ofSubscriber($User)->get();
            dd($get_user_membership_type);
            $membership_name = $get_user_membership_type[0]->slug;
            $narrative_remaining = $User->planSubscription($membership_name)->getFeatureRemainings('narrative');
            dd($narrative_remaining);


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

$router->get('/feature_form', [
    'as' => 'crud_index',
    'uses' => "FeatureController@index",
]);
$router->post('/feature_form', [
    'as' => 'content_create',
    'uses' => "FeatureController@create",
]);
$router->get('/edit', [

    'uses' => "FeatureController@edit",
]);

$router->post('/edit', [
    'as' => 'content_update',
    'uses' => "FeatureController@update",
]);
$router->get('/delete', [
    'as' => 'content_delete',
    'uses' => "FeatureController@destroy",
]);
//$router->post('/feature_form', ['uses' => FeatureController::class.'@create', 'as' => 'content_create']);
//$router->get('/edit/{id}', ['uses' => FeatureController::class.'@edit', 'as' => 'content_edit']);
//$router->post('/edit/{id}', ['uses' => FeatureController::class.'@update', 'as' => 'content_update']);
//$router->get('/delete/{id}', ['uses' => FeatureController::class.'@destroy', 'as' => 'content_delete']);
