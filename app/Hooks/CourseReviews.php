<?php

namespace App\Hooks;


use App\Models\CourseReviewsData;

class CourseReviews
{


    public function init(): void
    {
        add_action('save_post', [$this, "insert_data"], PHP_INT_MAX, 3);
        add_action('before_delete_post', [$this, "delete_data"], PHP_INT_MAX, 2);
    }

    function insert_data($post_id, $post, $update)
    {
        if ($post->post_type === "product") {
            $courses = get_post_meta($post_id, '_related_course', true);

            if (!empty($courses)) {
                CourseReviewsData::where("product_id", $post_id)->delete();
                foreach ($courses as $course_id) {
                    CourseReviewsData::create(["course_id" => $course_id, "product_id" => $post_id]);

                }
            }
        }
    }

    function delete_data($post_id, $post)
    {
        if ($post->post_type == 'product') {
            CourseReviewsData::where("product_id", $post_id)->delete();
        } else if ($post->post_type == 'sfwd-courses') {
            CourseReviewsData::where("course_id", $post_id)->delete();
        }
    }
}
