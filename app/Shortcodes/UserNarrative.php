<?php

namespace App\Shortcodes;


class UserNarrative
{


    /**
     * Shortcode Name
     */

    public $name = "user_narrative";

    /**
     * Shortcode Render function to generate output.
     */
    public function render()
    {

        bundle("user_narrative", ['jquery']);
        wp_localize_script('black-sheep-community-js.user_narrative.0','user_narrative',[
            'save_narrative_url' => bsc_ajax_route_url('save_user_narrative',true, 'checkform')
        ]);

        return view("shortcodes.user-narrative")->render();
    }

}
