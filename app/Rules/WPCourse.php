<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class WPCourse implements Rule
{


     /**
         * Create a new rule instance.
         *
         * @return void
         */
        public function __construct()
        {

        }
 /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $post = get_post($value);
        if(is_object($post) && $post->post_type==="sfwd-courses"){
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Invalid Course Id";
    }
}
