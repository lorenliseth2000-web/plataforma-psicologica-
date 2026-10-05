<?php

namespace App\Policies;

use App\Models\EmotionalLog;
use App\Models\User;

class EmotionalLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EmotionalLog $emotionalLog): bool
    {
        return $user->id === $emotionalLog->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EmotionalLog $emotionalLog): bool
    {
        return $user->id === $emotionalLog->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EmotionalLog $emotionalLog): bool
    {
        return $user->id === $emotionalLog->user_id || $user->isAdmin();
    }
}
