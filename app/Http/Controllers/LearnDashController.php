<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LearnDashController extends Controller
{


    public function redirect_to_thankyou_page(Request $request){
        $data = $this->validate($request,[
            "user_id" => ["required","exists:users,ID"],
            "course_id"=>["required","exists:posts,ID"]
        ]);

        $user_id   		= $data['user_id'];
        $course_id 		= $data["course_id"];
        $course_certificate_link = learndash_get_course_certificate_link(  $course_id );
        $getcourse  	= get_post($course_id);
        $title 			= $getcourse->post_title;
        $data 			= wp_get_current_user();
        $name 			= $data->display_name;
        return view('learndash_bonus_certificate/thankyou')->with(["course_link"=>$course_certificate_link, "course_title"=>$title,"user_name"=>$name]);

    }
}
