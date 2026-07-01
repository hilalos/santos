<?php

namespace App\Http\Controllers\Admin\Users;

use App\Enums\UserPlan;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\BulkActionRequest;
use App\Models\User;
use App\Notifications\AdminMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class UserBulkActionController extends Controller
{
    public function __invoke(BulkActionRequest $request): RedirectResponse
    {
        $users = User::whereIn('id', $request->input('user_ids'));
        $count = $users->count();

        match ($request->string('action')->toString()) {
            'assign_plan' => $users->update(['plan' => UserPlan::from($request->string('plan')->toString())]),
            'assign_role' => $users->update(['role' => UserRole::from($request->string('role')->toString())]),
            'verify_email' => $users->whereNull('email_verified_at')->update(['email_verified_at' => now()]),
            'suspend' => $users->update(['status' => UserStatus::Suspended]),
            'activate' => $users->update(['status' => UserStatus::Active]),
            'delete' => $this->deleteEach($users),
            'notify' => Notification::send(
                $users->get(),
                new AdminMessage($request->string('subject')->toString(), $request->string('body')->toString())
            ),
            default => null,
        };

        return back()->with('success', "Bulk action applied to {$count} user(s).");
    }

    private function deleteEach(Builder $users): void
    {
        $users->get()->each->delete();
    }
}
