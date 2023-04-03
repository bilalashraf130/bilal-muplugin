<?php

namespace App\Hooks;


class ProductSubscriptionType extends \WC_Product
{

    public function __construct( $product = 0 ) {
        $this->supports[] = 'ajax_add_to_cart';
        $this->product_type = 'Product_subscription';
        parent::__construct( $product );
    }
    /**
     * Base init function.
     * You can use add_action and add_filter hooks here
     */
     public function init(): void
        {

        }
    public function is_sold_individually(){


        return true;
    }
    public function is_virtual() {
        return true;
    }

    public function is_downloadable() {
        return true;
    }
    public function get_type() {
        return 'product_subscription';
    }
}
