<?php

namespace App\Policies;

use App\Models\TechniqueSession;
use App\Models\User;

class TechniqueSessionPolicy
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
    public function view(User $user, TechniqueSession $session): bool
    {
        return $user->id === $session->user_id || $user->isAdmin();
    }
}
