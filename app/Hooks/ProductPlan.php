<?php

namespace App\Hooks;


use App\Models\ProductPlanPivot;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use WPWhales\Subscriptions\Models\Plan;
use Rinvex\Subscriptions\Models\PlanFeature;
use App\Models\PostMeta;
use WPWhales\Subscriptions\Models\Feature;
use Illuminate\Support\Str;
class ProductPlan
{


    /**
     * Base init function.
     * You can use add_action and add_filter hooks here
     */
     public function init(): void
        {
            add_action('wp_insert_post_data', [$this, "insert_data"], PHP_INT_MAX, 2);
//            add_action('before_delete_post', [$this, "delete_data"], PHP_INT_MAX, 2);
        }


    function insert_data($data, $postarr)
    {
        if (!empty($postarr) && is_array($postarr) && $postarr['post_status'] === 'publish') {


            $request = app('request')->all();
            $validator = Validator::make($request, [
                "post_ID" => 'required',
                "_regular_price" => 'required',
                "memberships" => 'required|string',
                "description" => 'required|string',
                "trial_period" => 'required',
                "trial_interval" => 'required',
                "invoice_period" => 'required',
                "feature" => 'required|array',
                "feature.*.id"=>'required',
                "feature.*.value"=>'required',
                "feature.*.resettable_period"=>'required',
                "feature.*.resettable_interval"=>'required|string',
                "invoice_interval" => 'required',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                if(!empty($errors)){

                    wp_die($errors[0]);
                }
            }
            $plan = new Plan([
                'slug' => $request['memberships'],
                'name' => $request['memberships'],
                'description' => $request['description'],
                'is_active' => 1,
                'price' => $request['_regular_price'],
                'signup_fee' => 0,
                'sort_order' => 1,
                'currency' => $request['_product_base_currency'],
                'product_id' => $request['post_ID'],
                'trial_period' => $request['trial_period'],
                'trial_interval' => $request['trial_interval'],
                'invoice_period' => $request['invoice_period'],
                'invoice_interval' => $request['invoice_interval'],
            ]);
            $plan->save();
            $arr_of_features = $request['feature'];
            foreach ($arr_of_features as $feature) {


                $plan->features()->attach($feature['id'], [
                    "value" => $feature['value'] == '2*price'? 2*$plan->price:$feature['value'],
                    "resettable_period" => $feature['resettable_period'] ?? 0,
                    "resettable_interval" => $feature['resettable_interval'] ?? 'month'
                ]);
            }
            if(isset($plan["product_id"])){

                update_post_meta($plan['product_id'],'subscription_plan_id',$plan->id);

//                $add_meta = PostMeta::updateOrCreate([
//                    "meta_key"=>"subscription_plan_id",
//                    "post_id"=>$plan["product_id"]
//                ],[
//                    "meta_value"=>$plan->id
//                ]);
//                dd($plan['product_id'],$add_meta);
            }


        }
        return $data;

    }
}
