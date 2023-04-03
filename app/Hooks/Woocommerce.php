<?php

namespace App\Hooks;
use Illuminate\Support\Facades\Auth;
use Rinvex\Subscriptions\Models\Plan;
use Carbon\Carbon;
use Rinvex\Subscriptions\Providers\SubscriptionsServiceProvider;
use Rinvex\Subscriptions\Traits\HasPlanSubscriptions;
use Rinvex\Subscriptions\Models\PlanSubscription;
use App\Models\User;
class Woocommerce
{


    public function init(): void
    {
        add_action( 'woocommerce_after_checkout_validation', [$this,"woocommerce_after_checkout_validation"], 10, 2 );
        add_filter( 'woocommerce_checkout_fields', [$this,"woocommerce_checkout_fields"],PHP_INT_MAX );
        add_action( "woocommerce_checkout_order_processed",[$this,"woocommerce_checkout_order_processed"],PHP_INT_MAX,3);
        add_action( 'woocommerce_order_status_completed', [$this,"woocommerce_order_status_completed"], 20);
        add_action( 'woocommerce_checkout_process', [$this,"my_custom_checkout_field_validation"], PHP_INT_MAX);
        add_filter("woocommerce_checkout_fields", [$this,"custom_override_checkout_fields"], PHP_INT_MAX);
        add_action("wp_enqueue_scripts", [$this,"dequeue_qd_checkout_js_file"], PHP_INT_MAX);
        add_filter("woocommerce_product_class", [$this,"get_product_class"], PHP_INT_MAX,3);
        add_action("woocommerce_checkout_update_order_meta", [$this,"save_field"], 12);
        add_filter( 'product_type_selector', [$this,"add_product_type"],PHP_INT_MAX );
        add_filter( 'woocommerce_product_data_tabs', [$this,"demo_product_tab"],PHP_INT_MAX );
        add_action("woocommerce_product_data_panels", [$this,"demo_product_tab_product_tab_content"], 12);
        add_action("admin_footer", [$this,"my_subscription_custom_js"], 12);
        add_action("woocommerce_process_product_meta", [$this,"save_my_subscription_product_settings"], 12);
        add_action("woocommerce_Product_subscription_add_to_cart", [$this,"add_to_cart_button"], 12);
        add_action("woocommerce_order_status_completed", [$this,"assign_membership_to_user"],PHP_INT_MAX);

        /**

        Adds an action hook to run a custom function 'bsi_cart_total_after' after WooCommerce calculates cart totals.
        @param string $hook_name The name of the action hook to add.
        @param string $callback The name of the callback function to run when the action hook is triggered.
        @param int $priority Optional. The priority of the action hook, used to determine the order in which multiple hooks are executed. Defaults to PHP_INT_MAX.
        @param int $args Optional. The number of arguments that should be passed to the callback function. Defaults to 1.
        @return void
         */
        add_action("woocommerce_after_calculate_totals",[$this,"bsi_cart_total_after"] , PHP_INT_MAX, 1);

    }

    /**

    Assign membership to user
     */
    public function assign_membership_to_user($order_id )
    {
//        $User_object = User::find(get_current_user_id());
        $order = wc_get_order($order_id);
        $items = $order->get_items();
        $user_id = get_current_user_id();
        foreach ($items as $item) {
            $product_id = $item->get_product_id();
        }
        $membership_type = get_post_meta($product_id, 'membership_type', true);

        if ($membership_type == 'full_time') {

            $plan = app('rinvex.subscriptions.plan')->find(68);
            $subscription = new PlanSubscription();
            $subscription->subscriber_id = $user_id;
            $subscription->subscriber_type = 'App\Models\User';
            $subscription->slug = $plan->slug;
            $subscription->plan_id = $plan->id;
            $subscription->name = $plan->name;
            $subscription->starts_at = Carbon::now();
            $subscription->ends_at = Carbon::now()->addYear();
            $subscription->save();
        } else if($membership_type == 'life_time'){

            $plan = app('rinvex.subscriptions.plan')->find(69);
            $subscription = new PlanSubscription();
            $subscription->subscriber_id = get_class($User_object);
            $subscription->subscriber_type = 'App\Models\User';
            $subscription->slug = $plan->slug;
            $subscription->plan_id = $plan->id;
            $subscription->name = $plan->name;
            $subscription->starts_at = Carbon::now();
            $subscription->ends_at = Carbon::now()->addYear();
            $subscription->save();

        } else {
            $find = PlanSubscription::where('subscriber_id', $user_id)->count();
            if ($find == 0) {
                $plan = app('rinvex.subscriptions.plan')->find(43);
                $subscription = new PlanSubscription();
                $subscription->subscriber_id = $user_id;
                $subscription->subscriber_type = 'App\Models\User';
                $subscription->slug = $plan->slug;
                $subscription->plan_id = $plan->id;
                $subscription->name = $plan->name;
                $subscription->starts_at = Carbon::now();
                $subscription->ends_at = Carbon::now()->addYear();
                $subscription->save();
            }
        }

    }
    /**

    Adds custom product Type product subscription
     */

    public function get_product_class( $classname, $product_type, $post_type ){

        if ($product_type == 'product_subscription') {

            $classname = '\App\Hooks\ProductSubscriptionType';
        }
        return $classname;

    }
    public function add_product_type( $types ){
        $types[ 'Product_subscription' ] = __( 'Product subscription', 'ps_product' );
        return $types;
    }
    public function demo_product_tab( $tabs ){
        $tabs['demo'] = array(
            'label'     => __( 'Select Membership ', 'ps_product' ),
            'target' => 'demo_product_options',
            'class'  => 'show_if_demo_product',
        );
        return $tabs;
    }
    public function demo_product_tab_product_tab_content(){
        ?><div id='demo_product_options' class='panel woocommerce_options_panel'><?php
        ?><div class='options_group'><?php
        woocommerce_wp_select(
            array(
                'id' => 'memberships',
                'label' => __( 'Memberships', 'membership_product' ),
                'placeholder' => '',
                'desc_tip' => 'true',
                'description' => __( 'Select Membership.', 'membership_product' ),
                'options' => array(

                    'full_time' => __( 'Full Time', 'membership_product' ),
                    'life_time' => __( 'Life Time', 'membership_product' ),
                ),
            )
        );
        ?></div>
        </div><?php
    }
    function wcs_hide_attributes_data_panel( $tabs) {
        // Other default values for 'attribute' are; general, inventory, shipping, linked_product, variations, advanced
        $tabs['general']['class'][] = 'show_if_Product_subscription';


        return $tabs;

    }
    function my_subscription_custom_js() {
        if ( 'product' != get_post_type() ) :
            return;
        endif;
        ?><script type='text/javascript'>
            jQuery( document ).ready( function() {

                jQuery( '.options_group.pricing' ).addClass( 'show_if_Product_subscription' ).show();
                jQuery( '.general_options' ).show();
            });
        </script><?php
    }
    public function save_my_subscription_product_settings( $post_id ){


        $my_subscription_product_info = $_POST['memberships'];

        if( !empty( $my_subscription_product_info ) ) {

            update_post_meta( $post_id, 'membership_type', esc_attr( $my_subscription_product_info ) );
        }
    }
    public function add_to_cart_button(){

        do_action( 'woocommerce_simple_add_to_cart' );

    }
    /**

    Callback function to update taxes in the cart after checkout using the WooCommerce Quaderno  plugin.

    @param object $cart The WooCommerce cart object.

    @return void
     */

    public function bsi_cart_total_after($cart){



        global $wp_filter;
        if(isset($_GET["wc-ajax"]) && $_GET["wc-ajax"]==="wc_ppcp_frontend_request"){
            remove_action("woocommerce_after_calculate_totals",[$this,"bsi_cart_total_after"],PHP_INT_MAX);


            $wc = new \WC_QD_Checkout_Vat();
            $wc->update_taxes_on_update_order_review(http_build_query($_POST));
            WC()->cart->calculate_totals();

        }

    }
    public function save_field( $order_id ) {

        if ( ! empty( $_POST['tax_id'] ) ) {
            // Remove non-word characters
            $tax_id = preg_replace('/\W/', '', sanitize_text_field( $_POST['tax_id'] ));

            $order = wc_get_order( $order_id );
            if(isset($_POST['checkbox_options'])){
                if (  $_POST['billing_company'] ) {
                    update_post_meta( $order_id, 'tax_id', $tax_id );
                }
            }
        }
    }
    public function dequeue_qd_checkout_js_file(){

        global $wp_scripts;
        $wp_scripts->dequeue('wc_qd_checkout_js');

    }
    function custom_override_checkout_fields($fields) {
        $fields['billing']['billing_first_name']['priority'] = 1;
        $fields['billing']['billing_last_name']['priority'] = 2;
        $fields['billing']['billing_company']['priority'] = 130;
        $fields['billing']['billing_country']['priority'] = 4;
        $fields['billing']['billing_state']['priority'] = 5;
        $fields['billing']['billing_address_1']['priority'] = 6;
        $fields['billing']['billing_address_2']['priority'] = 7;
        $fields['billing']['billing_city']['priority'] = 8;
        $fields['billing']['billing_postcode']['priority'] = 9;
        $fields['billing']['billing_email']['priority'] = 10;
        $fields['billing']['billing_phone']['priority'] = 11;
        return $fields;
    }


    public function woocommerce_checkout_order_processed($order_id, $posted_data, $order){
        //Save timestamps for terms and policy
        $customer_location = esc_html($_POST["customer_location"]);
        update_post_meta($order_id,"term_acceptance",time());
        update_post_meta($order_id,"policy_acceptance",time());
        update_post_meta($order_id,"customer_location",$customer_location);
    }
    public function woocommerce_checkout_fields( $fields ) {

        $fields['billing']['checkbox_options'] = array(
            'type'      => 'checkbox',
            'class'     => array('input-checkbox'),
            'label'     => __('are you an organization ?'),
            'priority'    => 125, // Priority sorting option
        );

        return $fields;
    }
    public function woocommerce_after_checkout_validation( $fields, $errors ){

        if(!isset($_POST["policy"]) || empty($_POST["policy"])){
            $errors->add( 'validation', 'Please read and accept the Privary Policy to proceed with your order.' );

        }
    }

    public function woocommerce_order_status_completed( $order_id ) {

        $order = wc_get_order($order_id);

        $user = $order->get_user();

        $message_template = "Course {name} is purchased by a existing user on members site via non members site.";

        foreach ($order->get_items() as $item) {
            $courses = get_post_meta($item->get_product_id(), "_related_course", true);

            foreach ($courses as $course_id) {
                if (!empty($course_id)) {
                    $message = str_replace("{name}", get_the_title($course_id), $message_template);
                    do_action('schedule_act_event_tracking', "course_id_{$course_id}_purchased", $user->user_email, $user->first_name, $user->last_name, $message, 120);
                }
            }
        }


    }



    public function my_custom_checkout_field_validation(){

        if(isset($_POST['checkbox_options'])){
            if ( ! $_POST['billing_company'] ) {
                wc_add_notice(__('Billing Company is required.'), 'error');
            }
            if ( ! $_POST['tax_id'] ) {
                wc_add_notice(__('Tax ID is required.'), 'error');
            }
        }
    }

}
