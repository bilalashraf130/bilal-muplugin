<?php

namespace App\Hooks;


class AdminSettings
{


    public function init(): void
    {

        add_action('admin_init', [$this, "admin_settings"], PHP_INT_MAX);




    }
    public function admin_settings(){

        register_setting(
            'general',
            'main_website_url',
            'esc_text' // <--- Customize this if there are multiple fields
        );
        add_settings_section(
            'main-site-settings',
            'Main Site Settings',
            '__return_false',
            'general'
        );
        add_settings_field(
            'main_website_url',
            'Enter Main Site Url(without leading slash)',
            [$this, "main_website_url"],
            'general',
            'main-site-settings',
            [
                "id"=>"main_website_url",
                "option_name"=>"main_website_url"
            ]
        );
    }
    public function main_website_url($val)
    {

        $id = $val['id'];
        $option_name = $val['option_name'];
        ?>
        <input
            type="text"
            name="<?php echo esc_attr($option_name) ?>"
            id="<?php echo esc_attr($id) ?>"
            value="<?php echo esc_attr(get_option($option_name)) ?>"
        />

        <?php
    }

}
