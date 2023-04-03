<?php

namespace App\Policies;

use App\Models\FeatureRelationship;
use App\Models\User;
use App\Models\UserMembership;
use App\Models\UserNarrative;

class UserNarrativePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function Check_free_narrative(User $user)
    {
        $User = User::find(get_current_user_id());
        $get_user_membership_type = app('rinvex.subscriptions.plan_subscription')->ofSubscriber($User)->get();
        $membership_name = $get_user_membership_type[0]->slug;
        $narrative_remaining = $User->planSubscription($membership_name)->getFeatureRemainings('narrative');
        dd($narrative_remaining);
        if($narrative_remaining != 0){
            dd($narrative_remaining);
            $User->planSubscription($membership_name)->recordFeatureUsage('narrative');
            return true;
        }
    }
}
