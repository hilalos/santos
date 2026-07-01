<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\SendMessageRequest;
use App\Models\User;
use App\Notifications\AdminMessage;
use Illuminate\Http\RedirectResponse;

class UserNotificationController extends Controller
{
    public function store(SendMessageRequest $request, User $user): RedirectResponse
    {
        $user->notify(new AdminMessage(
            $request->string('subject')->toString(),
            $request->string('body')->toString(),
        ));

        return back()->with('success', "Message sent to {$user->name}.");
    }
}
