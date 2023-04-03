<?php

namespace App\Http\Controllers\Ajax;

use App\Helpers\WoocommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\BonusCertificateData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BonusController extends Controller
{
    public function send_feedback_mail($feedback_data)
    {
        $current_user = wp_get_current_user();
        $to = 'lonneke.oostland@bvtf.nl';
        $subject = "New Course Feedback";
        $message = view("emails/bonus_certificate_mail")->with("feedback_data", $feedback_data)->render();
        $admin_email = get_option('admin_email');
        $headers[] = 'From: ' . get_bloginfo('name') . ' <' . esc_attr($admin_email) . '>';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        return wp_mail($to, $subject, $message, $headers, '');
    }

    public function get_feedback_from_user(Request $request)
    {
        $data = $this->validate($request, [
            "linkedin_review" => 'required|image',
            "course_review_star" => 'required',
            "course_feedback" => ["required", "string"],
            "feedback" => ["required", "string"],

        ]);
        $user_id = get_current_user_id();
        $course_id = $this->request->course_id;
        $image = $data['linkedin_review'];
        $hashcode = $image->hashName();
        $internal_feedback = $data['feedback'];
        $upload = wp_upload_dir();
        $attachment_url = $upload['baseurl'] . "/course_feedback/" . $course_id . "/" . $user_id . "/" . $hashcode;
        $feedback_data = array();
        $feedback_data['internal_feedback'] = $internal_feedback;
        $feedback_data['attachment_url'] = $attachment_url;
        $check = BonusCertificateData::where('user_id', $user_id)->where('course_id', $course_id)->get();
        if (sizeof($check) == 0) {

            $product_review = WoocommerceHelper::insert_product_review($course_id, $data['course_feedback'], $data['course_review_star']);
            if (empty($product_review)) {

                return "Cannot post the review";
            }

            //WE CAN ALSO USE REQUEST SAVE METHOD
            Storage::put("course_feedback/$course_id/$user_id/" . $hashcode,$image->get());

            $dataarr = array('linkedin_review' => $hashcode, 'course_star_review' => $data['course_review_star'], 'course_feedback' => $data['course_feedback'], 'internal_feedback' => $data['feedback'], 'user_id' => $user_id, 'course_id' => $course_id);
            $test = BonusCertificateData::create($dataarr);

            $this->send_feedback_mail($feedback_data);
            return "done";

        } else {
            return "Already Saved";
        }
    }

}
