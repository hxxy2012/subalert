<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Subscription $subscription)
    {
        return $user->id === $subscription->user_id;
    }

    public function update(User $user, Subscription $subscription)
    {
        return $user->id === $subscription->user_id;
    }

    public function delete(User $user, Subscription $subscription)
    {
        return $user->id === $subscription->user_id;
    }
}