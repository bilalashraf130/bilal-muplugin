<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Admin-ajax.php Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get("get-course",[
    "noauth"=>true,
    "uses"=>"CoursesController@get_course"
]);
$router->post("enroll_in_course",[
    "noauth"=>true,
    "as"=>"enroll_in_course",
    "uses"=>"CoursesController@enroll_in_course",
    "withoutMiddleware"=>[\Laravel\Lumen\Middlewares\VerifyCsrfToken::class]
]);

$router->get("login_user",[
    "noauth"=>true,
    "auth"=>true,
    "middleware"=>["signed","throttle:3,1"],
    "as"=>"jwt_login_user",
    "uses"=>"UserController@jwt_login_user"
]);


$router->post("send_ajax_verification_code",[
    "as"=>"send_ajax_verification_code",
    "uses"=>"UserController@send_ajax_verification_code"
]);


$router->post("bonus_certificate",[
    "as"=>"bonus_certificate",
    "uses"=>"BonusController@get_feedback_from_user"
]);

