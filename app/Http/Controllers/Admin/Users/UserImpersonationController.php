<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserImpersonationController extends Controller
{
    /**
     * Log in as the given user, remembering who the real admin was.
     */
    public function store(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is_admin, 403, 'Admins cannot be impersonated.');
        abort_if($user->id === Auth::id(), 403);

        $request->session()->put('impersonator_id', Auth::id());

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "You are now viewing the app as {$user->name}.");
    }

    /**
     * Return to the original admin session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $adminId = $request->session()->pull('impersonator_id');

        abort_unless($adminId, 404);

        Auth::loginUsingId($adminId);

        return redirect()->route('admin.users.index')->with('success', 'Returned to your admin account.');
    }
}
