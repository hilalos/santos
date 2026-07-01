<?php

namespace App\Http\Controllers\Admin\Users;

use App\Enums\UserPlan;
use App\Enums\UserStatus;
use App\Filters\Admin\UserFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\StoreUserRequest;
use App\Http\Requests\Admin\Users\UpdateStatusRequest;
use App\Http\Requests\Admin\Users\UpdateUserRequest;
use App\Models\User;
use App\Services\Admin\UserStatsService;
use App\Support\DeviceParser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    public function index(Request $request, UserFilter $filter, UserStatsService $stats): View
    {
        try {
            $users = $filter->apply(User::query())
                ->with(['loginHistories', 'notes.author'])
                ->paginate(15)
                ->withQueryString();

            $sessionsByUser = DB::table('sessions')
                ->whereIn('user_id', $users->pluck('id'))
                ->orderByDesc('last_activity')
                ->get()
                ->groupBy('user_id');
        } catch (Throwable $e) {
            report($e);

            return view('admin.users.index', [
                'users' => null,
                'stats' => null,
                'drawerPayload' => [],
                'error' => true,
            ]);
        }

        return view('admin.users.index', [
            'users' => $users,
            'stats' => $stats->summary(),
            'drawerPayload' => $this->buildDrawerPayload($users, $sessionsByUser),
            'error' => false,
        ]);
    }

    /**
     * Build the per-user JSON payload used to render the details drawer
     * client-side, without an extra request when a row is clicked.
     *
     * @return array<int, array<string, mixed>>
     */
    private function buildDrawerPayload(LengthAwarePaginator $users, Collection $sessionsByUser): array
    {
        return $users->getCollection()->mapWithKeys(function (User $user) use ($sessionsByUser) {
            return [$user->id => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->label(),
                'plan' => $user->plan->label(),
                'planColor' => $user->plan->badgeColor(),
                'status' => $user->status->label(),
                'statusColor' => $user->status->badgeColor(),
                'country' => $user->country,
                'countryName' => config('countries')[$user->country] ?? $user->country,
                'phone' => $user->phone,
                'phoneVerified' => (bool) $user->phone_verified_at,
                'emailVerified' => (bool) $user->email_verified_at,
                'twoFactorEnabled' => $user->two_factor_enabled,
                'registeredAt' => $user->created_at?->format('M j, Y g:ia'),
                'lastLoginAt' => $user->last_login_at?->diffForHumans() ?? 'Never',
                'logins' => $user->loginHistories->map(fn ($h) => [
                    'ip' => $h->ip_address,
                    'device' => $h->device(),
                    'location' => $h->location,
                    'at' => $h->created_at->diffForHumans(),
                ])->all(),
                'notes' => $user->notes->map(fn ($n) => [
                    'author' => $n->author?->name ?? 'System',
                    'body' => $n->body,
                    'at' => $n->created_at->diffForHumans(),
                ])->all(),
                'sessions' => ($sessionsByUser->get($user->id) ?? collect())->map(fn ($s) => [
                    'ip' => $s->ip_address,
                    'device' => DeviceParser::label($s->user_agent ?? ''),
                    'lastActivity' => \Illuminate\Support\Carbon::createFromTimestamp($s->last_activity)->diffForHumans(),
                ])->values()->all(),
            ]];
        })->all();
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            ...$request->validated(),
            'password' => Hash::make($request->string('password')),
        ]);

        return back()->with('success', 'User created successfully.');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return back()->with('success', "{$user->name} updated successfully.");
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return back()->with('success', "{$user->name} deleted.");
    }

    public function updateStatus(UpdateStatusRequest $request, User $user): RedirectResponse
    {
        $user->update(['status' => UserStatus::from($request->string('status')->toString())]);

        return back()->with('success', "{$user->name} is now ".$user->status->label().'.');
    }

    public function updatePlan(Request $request, User $user): RedirectResponse
    {
        $request->validate(['plan' => ['required', new Enum(UserPlan::class)]]);

        $user->update(['plan' => UserPlan::from($request->string('plan')->toString())]);

        return back()->with('success', "{$user->name}'s plan changed to ".$user->plan->label().'.');
    }

    public function verifyEmail(User $user): RedirectResponse
    {
        $user->forceFill(['email_verified_at' => now()])->save();

        return back()->with('success', "{$user->name}'s email has been verified.");
    }

    public function resetPassword(User $user): RedirectResponse
    {
        Password::sendResetLink(['email' => $user->email]);

        return back()->with('success', "Password reset link sent to {$user->email}.");
    }
}
