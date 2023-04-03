<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use App\Models\Course;
use App\Models\CourseReviewsData;
use App\Rules\WPCourse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CoursesController extends Controller
{
    public function get_course(Request $request){
        $data = $this->validate($request,[
            "course_id" => ["required", new WPCourse()]
        ]);
        $course_id = $data['course_id'];
        $product_id = CourseReviewsData::select('product_id')->where('course_id', $course_id)->first();
        $id = $product_id->product_id;
        $done =  Comments::whereHas('product',function($query) use($id){
            $query->where("ID",$id);
        })->where("comment_approved",1)->orderBy('comment_date', 'desc')->take(10)->get();
        $newArray = $done->map(function($item){
            return [
                'comment_author' => $item->comment_author,
                'comment_content' => $item->comment_content,
                'comment_date' => Carbon::createFromTimestamp(strtotime($item->comment_date))->diffForHumans(),
                'rating' => get_comment_meta($item->comment_ID,"rating",true),
                'image_url' => bp_core_fetch_avatar (
                    array(  'item_id' => $item->user_id, // id of user for desired avatar
                        'type'    => 'full',
                        'html'   => FALSE     // FALSE = return url, TRUE (default) = return img html
                    )
                ),
            ];
        });
        $course = Course::with(["contents" => function ($query) {
            $query->select("meta_key", "meta_value", "post_id")->whereHas("post", function ($query) {
                $query->whereIn("post_type", ["sfwd-lessons", "sfwd-topics"]);

            });
        }, "contents.post" => function ($query) {
            $query->select("ID", "post_title", "post_type");
        }

            , "meta" => function ($query) {
                $query->whereIn("meta_key", ["_buddyboss_lms_course_video", "_ld_course_steps_count"]);
            }, "user" => function ($query) {
                $query->withCount("courses");
            }])
            ->where("ID", $data["course_id"])->first();

        $course["enrollies"] = $course->enrollies;
        $course["post_date"] = date("d F Y",strtotime($course["post_date"]));
        $course["short_description"] = get_field("main_site_description", $data["course_id"]);
        $course['comment_data'] = $newArray;
        return $course;


    }


    public function enroll_in_course(Request $request){

        $data = $this->validate($request,[
            "first_name" => ["required", "string"],
            "last_name" => ["required", "string"],
            "email" => ["required", "string"],
            "course_ids" => ["required", "array"],
            "course_ids.*" => ["required", "numeric", new WPCourse()]
        ]);

        //check if user exists or not
        if (email_exists($data["email"])) {
            //Get the user data and enroll him in all course ids fetched above

            $user = get_user_by("email", $data["email"]);
            $message_template = "Course {name} is purchased by a existing user on members site via non members site.";

            foreach ($data["course_ids"] as $course_id) {
                ld_update_course_access($user->ID, $course_id);
                $message = str_replace("{name}",get_the_title($course_id),$message_template);
                //HIT ACTIVE CAMPAIGN EVENTS IF REQUIRED
                do_action( 'schedule_act_event_tracking', "course_id_{$course_id}_purchased", $user->user_email, $user->first_name,$user->last_name,$message,120);
            }
            //redirect user to courses landing page
            $redirect_to = get_permalink(get_page_by_path('courses'));

            if(!empty(get_user_meta($user->ID,"force_password_change",true))){
                $redirect_to= route("forgot_password_change");
            }
            return [
                "success" => true,
                "message" => "Enrolled in courses",
                "redirect_url" => URL::signedAjaxRoute("jwt_login_user",["user_id"=>$user->ID,"redirect_to"=>$redirect_to],Carbon::now()->addMinutes(15))
            ];
        } else {
            $first_name = preg_replace("/[^a-zA-Z0-9]+/", "", $data["first_name"]);
            $last_name = preg_replace("/[^a-zA-Z0-9]+/", "", $data["last_name"]);
            $username = substr($first_name, 0, 10) . substr($last_name, 0, 10) . wp_generate_password(4, false, false);

            $user = wp_insert_user([
                "first_name" => $first_name,
                "last_name" => $last_name,
                "user_email" => $data["email"],
                "user_login" => $username,
                "user_pass" => wp_generate_password(12, false, false)
            ]);

            if (!is_wp_error($user)) {
                $user = get_userdata($user);
                $message_template = "Course {name} is purchased by a new user on members site via non members site.";

                foreach ($data["course_ids"] as $course_id) {
                    ld_update_course_access($user->ID, $course_id);
                    //HIT ACTIVE CAMPAIGN EVENTS IF REQUIRED
                    $message = str_replace("{name}",get_the_title($course_id),$message_template);
                    do_action( 'schedule_act_event_tracking', "course_id_{$course_id}_purchased", $user->user_email, $user->first_name,$user->last_name,$message,120);
                }

                //ADD A IDENTIFIER TO FORCE USER TO CHANGE PASSWORD ON MEMBERS SITE BEFORE ACCESSING ANYTHING ON THE WEBSITE
                update_user_meta($user->ID,"force_password_change",true);
                //redirect user to password reset
                $redirect_to = route("forgot_password_change");

                return  [
                    "success" => true,
                    "message" => "Enrolled in courses",
                    "redirect_url" => URL::signedAjaxRoute("jwt_login_user",["user_id"=>$user->ID],Carbon::now()->addMinutes(15)),
                ];
            }

            return [
                "success" => false,
                "message" => "Error in enrolling new user"
            ];

        }

    }
}
