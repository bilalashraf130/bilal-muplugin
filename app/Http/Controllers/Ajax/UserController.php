<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function jwt_login_user(Request $request){
        $data = $this->validate($request,[
            "user_id" => ["required", "numeric","exists:users,ID"],
            "redirect_to"=>["filled","url"]
        ]);

        $user_id = $data["user_id"];
        $user = get_userdata($user_id);

        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);

        if(isset($data["redirect_to"])){
            return redirect($data["redirect_to"]);
        }
        return redirect(home_url());
    }


    public function send_ajax_verification_code(){

        $current_user = wp_get_current_user();
        $user_timestamp = get_user_meta($current_user->ID, 'user_code_timestamp', true) ? get_user_meta($current_user->ID, 'user_code_timestamp', true) : time();
        $difference = time() - $user_timestamp;
        if($difference < 60)
            echo json_encode(array('success' => false, 'expiration' => false, 'time_left' => (60 - $difference)));
        else
            echo $this->send_verification_code() ? json_encode(array('success' => true, 'expiration' => true)): json_encode(array('success' => false, 'expiration' => true));
        die();


    }
    public function send_verification_code()
    {
        $current_user = wp_get_current_user();
        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        update_user_meta( $current_user->ID, 'user_activation_code', $verification_code );
        update_user_meta( $current_user->ID, 'user_code_timestamp', time());
        $to = $current_user->user_email;
        $subject = esc_html__('Email Verification', 'simple-job-board');
        $message =view("emails.verification")->with("code",$verification_code)->render();
        $admin_email = get_option( 'admin_email' );
        $headers[] = 'From: ' . get_bloginfo('name') . ' <' . esc_attr( $admin_email ) . '>';
        $headers[] = 'Reply-To: ' . $current_user->display_name . ' <' . $to . '>';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        return wp_mail($to, $subject, $message, $headers, '');
    }
}
