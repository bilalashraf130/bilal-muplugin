<?php


namespace App\Hooks;

use App\Models\ACTTrackingData;

class User
{
    public function __construct()
    {
    }

    public function init()
    {

        add_action('user_register', [$this, "user_register"], PHP_INT_MAX, 2);
        add_action('deleted_user', [$this, "deleted_user"], PHP_INT_MAX, 3);
        add_action('act_tracking_inactive_2_weeks', [$this, "act_tracking_inactive_2_weeks"]);
    }


    public function act_tracking_inactive_2_weeks()
    {
//		$numweeks = 2;
//		$user_query = new WP_User_Query(
//			array(
////		'meta_key' => 'wfls-last-login',
//				'meta_key' => 'learndash-last-login',
//				'meta_value' => time()- (60*60*24*7*$numweeks),
//				'meta_compare' => '<',
//			)
//		);
//
//		foreach ($user_query->get_results() as $userData) {
//
//		}

    }

    public function deleted_user($id, $reassign, $user)
    {

        $delete_rows = ACTTrackingData::where("user_email", $user->user_email)->delete();


    }

    public function user_register($user_id, $user_data)
    {

        $message = "New User Registered on Members Site";
        $user = get_userdata($user_id);

        $first_name = empty($user->first_name) ? xprofile_get_field_data("First Name", $user_id) : $user->first_name;
        $last_name = empty($user->last_name) ? xprofile_get_field_data("Last Name", $user_id) : $user->last_name;


        if(empty($first_name)){
            add_action('bp_core_signup_user',[$this,'bb_signup'],PHP_INT_MAX,5);

        }else{
            do_action('schedule_act_event_tracking', "user_registered", $user->user_email, $first_name, $last_name, $message, 120);

        }



    }


    public function bb_signup($user_id, $user_login, $user_password, $user_email, $usermeta){
        $message = "New User Registered on Members Site. BuddyBoss data updated";
        $user = get_userdata($user_id);
        $first_name = empty($user->first_name) ? xprofile_get_field_data("First Name", $user_id) : $user->first_name;
        $last_name = empty($user->last_name) ? xprofile_get_field_data("Last Name", $user_id) : $user->last_name;
        do_action('schedule_act_event_tracking', "user_registered", $user->user_email, $first_name, $last_name, $message, 120);


    }


}
