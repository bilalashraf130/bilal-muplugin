<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{

    public function force_password_reset()
    {
        $current_id = Auth::user()->ID;

        if (empty(get_user_meta($current_id, "force_password_change", true))) {
          return redirect(home_url());
        }
        return view("force-password-reset");
    }


    public function show_force_password_reset(Request $request)
    {

        $current_id = Auth::user()->ID;

        $data = $this->validate($request,[
            "password" => ["required", "min:8", "string", "confirmed"],
            "user_verification_code" => ["required", "numeric", new UserMetaValue("user_activation_code")],
        ]);



        $password = $data['password'];
        wp_set_password($password, $current_id);
        delete_user_meta($current_id, 'force_password_change');
        delete_user_meta($current_id, 'user_activation_code');
        delete_user_meta($current_id, 'user_code_timestamp');
        wp_set_auth_cookie($current_id);
        wp_redirect(get_site_url() . '/courses');
        die();
    }

}
