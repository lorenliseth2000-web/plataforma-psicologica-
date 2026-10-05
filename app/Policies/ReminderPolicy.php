<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserReminder;

class ReminderPolicy
{
    public function update(User $user, UserReminder $reminder): bool
    {
        return $user->id === $reminder->user_id;
    }

    public function delete(User $user, UserReminder $reminder): bool
    {
        return $user->id === $reminder->user_id;
    }
}
