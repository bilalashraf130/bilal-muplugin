<?php

namespace App\Models;


use Laravel\Lumen\Models\User as UserModel;
use WPWhales\Subscriptions\Traits\HasPlanSubscriptions;
class User extends UserModel
{

    use HasPlanSubscriptions;



}
