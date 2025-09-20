<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the given user can toggle moderator role for another user.
     */
    public function toggleModerator(User $authUser, User $targetUser): bool
    {
        // Only admins can assign/remove moderator role
        return $authUser->isAdmin() && $targetUser !== null;
    }
}
