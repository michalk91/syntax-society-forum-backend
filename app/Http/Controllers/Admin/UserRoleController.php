<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserRoleController extends Controller
{
    // Add the AuthorizesRequests trait to use $this->authorize()
    use AuthorizesRequests;

    /**
     * Toggle moderator role for a given user.
     * Only authorized users (admins) can perform this action.
     */
    public function toggleModerator(Request $request, User $user)
    {
        // Check permission using UserPolicy
        $this->authorize('toggleModerator', $user);

        // Toggle moderator flag (if no parameter in request, invert current value)
        $user->is_moderator = $request->boolean('is_moderator', !$user->is_moderator);
        $user->save();

        return response()->json([
            'message' => 'Moderator role updated for user: ' . $user->name,
            'user' => $user
        ]);
    }
}
