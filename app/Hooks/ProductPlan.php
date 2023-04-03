<?php

namespace App\Hooks;


use App\Models\ProductPlanPivot;

class ProductPlan
{


    /**
     * Base init function.
     * You can use add_action and add_filter hooks here
     */
     public function init(): void
        {
            add_action('save_post', [$this, "insert_data"], PHP_INT_MAX, 3);
            add_action('before_delete_post', [$this, "delete_data"], PHP_INT_MAX, 2);
        }


    function insert_data($post_id, $post, $update)
    {

        if ($post->post_type === "product") {
            $plan_type = get_post_meta($post_id, 'membership_type', true);
            $full_plan = ProductPlanPivot::where('product_id',$post_id)->where('plan_id',68)->count();
            $life_plan = ProductPlanPivot::where('product_id',$post_id)->where('plan_id',69)->count();
            if (!empty($plan_type)) {
                if($plan_type == 'full_time' && $full_plan == 0){

                    $product_plan = ProductPlanPivot::create([
                       'product_id'=> $post_id,
                        'plan_id'  => 44,
                    ]);
                } else if($plan_type == 'life_time'  && $life_plan == 0){

                    $product_plan = ProductPlanPivot::create([
                        'product_id'=> $post_id,
                        'plan_id'  => 45,
                    ]);
                }
            }
        }
    }

    function delete_data($post_id, $post)
    {
        if ($post->post_type == 'product') {
            ProductPlanPivot::where("product_id", $post_id)->delete();
        }
    }
}
