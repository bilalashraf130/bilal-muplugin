<?php

namespace App\Hooks;


class ForceResetPassword
{


    public function init(): void
    {
        add_action('template_redirect', [$this, "force_reset_pass"] , PHP_INT_MAX);

    }

    function force_reset_pass(){

        global $wpdb;
        $current_id 	= get_current_user_id();
        $table_name		= $wpdb->prefix . 'usermeta';
//		$check  		= $wpdb->get_results("SELECT * FROM $table_name WHERE meta_key like 'force_password_reset' AND user_id = '$current_id'  " );
        $check = get_user_meta($current_id, 'force_password_change', true);
//		dd($check);
        if($check && (stripos($_SERVER['REQUEST_URI'], 'force_password_change' ) === false)){
            wp_redirect(get_site_url() .'/force_password_change');
            die();
        }
    }
}
