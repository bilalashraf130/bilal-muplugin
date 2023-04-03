<?php

namespace App\Shortcodes;


use App\Models\Comments;

class CoursesReview
{


    /**
     * Shortcode Name
     */

    public $name = "courses_reviews";

    /**
     * Shortcode Render function to generate output.
     */
    public function render()
    {
        $course_id = get_the_ID();


        $done =  Comments::whereHas('product.course_products',function($query) use($course_id){
            $query->where('course_id', $course_id);
        }
        )->where("comment_approved",1)->orderBy('comment_date', 'desc')->paginate(5);
        $args = array(
            'base'         => @add_query_arg('paged','%#%'),
            'format'       => '?paged=%#%',
            'total'        => $done->lastPage(),
            'current'      => $done->currentPage(),
            'show_all'     => False,
            'end_size'     => 1,
            'mid_size'     => 2,
            'prev_next'    => True,
            'prev_text'    => __('Previous'),
            'next_text'    => __('Next'),
            'type'         => 'plain');


        return view("shortcodes.courses-review")->with(["comments"=>$done,"args"=>$args])->render();
    }

}
