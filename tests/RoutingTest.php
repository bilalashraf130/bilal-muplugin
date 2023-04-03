<?php

namespace Tests;

use Illuminate\Support\Facades\Http;

class RoutingTest extends TestCase
{


    public function test_new_user_enroll_course_forgot_password_test_confirm_course_landing_page()
    {



        $response = $this->post("/wp-admin/admin-ajax.php?action=enroll_in_course");
        //Send empty data to check validation
        $this->response->assertJson([
            "first_name" => [
                "The first name field is required."
            ],
            "last_name" => [
                "The last name field is required."
            ],
            "email" => [
                "The email field is required."
            ],
            "course_ids" => [
                "The course ids field is required."
            ],
        ]);
        //Now let's send actual random data
        $faker = \Faker\Factory::create();
        $response = $this->post("/wp-admin/admin-ajax.php?action=enroll_in_course",[
            "first_name"=>$faker->firstName,
            "last_name"=>$faker->lastName,
            "email"=>$faker->email,
            "course_ids"=>[
                "6381"
            ]
        ]);

        $this->response->assertJsonFragment([
           "success"=>true,
           "message"=>"Enrolled in courses"
        ]);

        $redirect_url = $this->httpResponse->json("redirect_url");
        //NOW REDIRECT TO THAT
         $this->get($redirect_url);
        dd($redirect_url,$this->httpResponse->body());

    }

}
