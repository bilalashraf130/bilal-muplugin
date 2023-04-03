<?php

namespace App\Shortcodes;


use App\Models\BonusCertificateData;
use Carbon\Carbon;

class BonusCertificate
{


    /**
     * Shortcode Name
     */

    public $name = "bonus_certificate";

    /**
     * Shortcode Render function to generate output.
     */
    public function render()
    {

        $user_id    = get_current_user_id();
        $course_id  = learndash_get_course_id();
        $url 		= URL::signedAjaxRoute("course_thank_you",["user_id" => get_current_user_id() , "course_id"=> $course_id],Carbon::now()->addMinutes(15));
        $check 		= BonusCertificateData::where('user_id',$user_id)->where('course_id',$course_id)->get();
        if( sizeof($check) == 0 ){

            bundle("bonus_certificate", ['jquery']);
            wp_localize_script('black-sheep-community-js.bonus_certificate.0','bonus_certificate',[
                'get_bonus_certificate_data' => bsc_ajax_route_url('bonus_certificate',true, 'getfeedback')
            ]);

            return view('shortcodes.learndash_bonus_certificate.bonus_certificate')->render();
        } else {
            return view('learndash_bonus_certificate.certificate-button')->with(["url"=>$url])->render();
        }


    }

}
