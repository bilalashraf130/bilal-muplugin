<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class UserMetaValue implements Rule
{

    private $meta_key;

    private $type_check;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($meta_key,$type_check = false)
    {
        $this->meta_key  = $meta_key;
        $this->type_check = $type_check;
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
        $user_meta_data = get_user_meta(get_current_user_id(), $this->meta_key, true);

        if($this->type_check){
            return $value===$user_meta_data;
        }

        return $value ==$user_meta_data;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {

        return "Invalid :attribute value";
    }
}
