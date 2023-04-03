<?php


namespace App\Helpers;

use App\Models\CourseReviewsData;

class WoocommerceHelper{

    public static function insert_product_review($course_id, $review, $rating )
    {


        $product = 	CourseReviewsData::where("course_id", $course_id)->first();

        if(empty($product)){
            return false;
        }
        $product_id = $product->product_id;
        $user = wp_get_current_user();
        $comment_data = array(
            'comment_post_ID' => $product_id,
            'comment_author' => $user->display_name,
            'comment_author_email' => $user->user_email,
            'comment_content' => $review,
            'comment_type' => 'review',
            'comment_parent' => 0,
            'user_id' => get_current_user_id(),
            'comment_author_IP' => '',
            'comment_agent' => '',
            'comment_approved' => 0,
        );
        $comment_id = wp_insert_comment($comment_data);

        if($comment_id ===false){
            return false;
        }

        update_comment_meta( $comment_id, 'rating', $rating );
        return $comment_id;
    }

}
