<?php

namespace App\Hooks;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Rinvex\Subscriptions\Models\PlanSubscription;

use App\Helpers\JWT;
use App\Models\BonusCertificateData;
use App\Models\User;
class LearnDash
{


    public function init(): void
    {

        add_action('learndash_course_completed', [$this, "learndash_course_completed"], 10);
        add_action('learndash_course_completed', [$this, "assign_free_membership"], 15);
        add_action('learndash_update_user_activity', [$this, "learndash_update_user_activity"], PHP_INT_MAX);
        add_action('learndash_before_course_completed', [$this, "check_feedback_status"] , PHP_INT_MAX);
        add_action('learndash_course_completed', [$this, "redirect_to_thankyou_page"], 20);

    }
//    public function assign_free_membership($course_data){
//
//            $user_id = get_current_user_id();
//            $find = PlanSubscription::where('subscriber_id',$user_id)->count();
//            if($find == 0){
//
//                $user = User::find($user_id);
//                $plan = app('rinvex.subscriptions.plan')->find(43);
//                $user->newPlanSubscription($plan->slug, $plan);
//
//            }
//
//    }
    function check_feedback_status(array $course_data){

        $user_id    = get_current_user_id();
        $course_id  = learndash_get_course_id();

        $check = BonusCertificateData::where('user_id',$user_id)->where('course_id',$course_id)->get();
        if( sizeof($check) == 0 ){
            dd();
        }
    }


    public function learndash_update_user_activity($args)
    {
        if ($args["activity_type"] === "lesson") {
            $course_id = $args["course_id"];
            $user_id = $args["user_id"];

            $lesson_count = \App\Models\UserActivity::where(["course_id"=>$course_id,"user_id"=>$user_id,"activity_type"=>"lesson"])->count();


            if ($lesson_count===1) {
                $user = get_userdata($user_id);
                $course_start_date = \Carbon\Carbon::createFromTimestamp(time())->format("d-M-Y");
                $message = "Course Started by {$user->user_email} on {$course_start_date}";

                do_action('schedule_act_event_tracking', "course_id_{$course_id}_started", $user->user_email, $user->first_name, $user->last_name, $message, 120);


            }
        }

    }

    public function learndash_course_completed($course_data)
    {

        $user = $course_data["user"];
        $course = $course_data["course"];
        $course_completion_time = $course_data["course_completed"];
        $course_completion_date = \Carbon\Carbon::createFromTimestamp($course_completion_time)->format("d-M-Y");
        $message = "Course completed by {$user->user_email} on {$course_completion_date}";

        do_action('schedule_act_event_tracking', "course_id_{$course->ID}_completed", $user->user_email, $user->first_name, $user->last_name, $message, 120);

    }

    public function redirect_to_thankyou_page($course_data){

        $user_id    = get_current_user_id();
        $course_id  = learndash_get_course_id();

        $url = URL::signedRoute(route("course_thank_you"), ["user_id" => get_current_user_id() , "course_id"=> $course_id],Carbon::now()->addMinutes(15));

        wp_redirect($url);
        die();
    }


}
