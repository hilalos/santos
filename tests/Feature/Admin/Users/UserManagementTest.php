<?php

namespace Tests\Feature\Admin\Users;

use App\Enums\UserPlan;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        // Pin plan/status so the admin fixture never accidentally collides
        // with plan/status assertions made about the users under test.
        return User::factory()->create([
            'is_admin' => true,
            'plan' => UserPlan::Enterprise,
            'status' => UserStatus::Active,
        ]);
    }

    public function test_index_page_loads_for_admin(): void
    {
        User::factory()->count(3)->create();

        $response = $this->actingAs($this->admin())->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('All Users');
    }

    public function test_search_filters_by_name_or_email(): void
    {
        $admin = $this->admin();
        User::factory()->create(['name' => 'Zelda Match', 'email' => 'zelda@findme.test']);
        User::factory()->create(['name' => 'Someone Else', 'email' => 'else@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['q' => 'Zelda']));

        $response->assertOk();
        $response->assertSee('zelda@findme.test');
        $response->assertDontSee('else@example.com');
    }

    public function test_status_filter_narrows_results(): void
    {
        $admin = $this->admin();
        User::factory()->create(['status' => UserStatus::Suspended, 'email' => 'suspended@example.com']);
        User::factory()->create(['status' => UserStatus::Active, 'email' => 'active@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['status' => 'suspended']));

        $response->assertSee('suspended@example.com');
        $response->assertDontSee('active@example.com');
    }

    public function test_admin_can_create_a_user(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Person',
            'email' => 'new-person@example.com',
            'password' => 'password123',
            'role' => 'member',
            'plan' => 'free',
            'status' => 'active',
            'country' => 'US',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'new-person@example.com']);
    }

    public function test_admin_can_update_a_user(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->patch(route('admin.users.update', $user), [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role' => 'manager',
            'plan' => 'pro',
            'country' => 'DE',
        ]);

        $response->assertRedirect();
        $this->assertSame('Updated Name', $user->fresh()->name);
        $this->assertSame('manager', $user->fresh()->role->value);
    }

    public function test_admin_can_soft_delete_a_user(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user));

        $response->assertRedirect();
        $this->assertSoftDeleted($user);
    }

    public function test_admin_can_change_user_status(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create(['status' => UserStatus::Active]);

        $response = $this->actingAs($admin)->post(route('admin.users.status', $user), [
            'status' => 'suspended',
        ]);

        $response->assertRedirect();
        $this->assertSame(UserStatus::Suspended, $user->fresh()->status);
    }

    public function test_admin_can_verify_a_users_email(): void
    {
        $admin = $this->admin();
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.verify-email', $user));

        $response->assertRedirect();
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_admin_can_add_a_note_to_a_user(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.notes.store', $user), [
            'body' => 'Important context about this user.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('user_notes', [
            'user_id' => $user->id,
            'author_id' => $admin->id,
            'body' => 'Important context about this user.',
        ]);
    }

    public function test_admin_can_bulk_suspend_users(): void
    {
        $admin = $this->admin();
        $users = User::factory()->count(3)->create(['status' => UserStatus::Active]);

        $response = $this->actingAs($admin)->post(route('admin.users.bulk'), [
            'action' => 'suspend',
            'user_ids' => $users->pluck('id')->all(),
        ]);

        $response->assertRedirect();
        $this->assertSame(3, User::where('status', UserStatus::Suspended)->count());
    }

    public function test_admin_can_bulk_assign_plan(): void
    {
        $admin = $this->admin();
        $users = User::factory()->count(2)->create(['plan' => UserPlan::Free]);

        $response = $this->actingAs($admin)->post(route('admin.users.bulk'), [
            'action' => 'assign_plan',
            'user_ids' => $users->pluck('id')->all(),
            'plan' => 'pro',
        ]);

        $response->assertRedirect();
        $this->assertSame(2, User::where('plan', UserPlan::Pro)->count());
    }

    public function test_admin_can_bulk_notify_users(): void
    {
        Notification::fake();

        $admin = $this->admin();
        $users = User::factory()->count(2)->create();

        $response = $this->actingAs($admin)->post(route('admin.users.bulk'), [
            'action' => 'notify',
            'user_ids' => $users->pluck('id')->all(),
            'subject' => 'Hello',
            'body' => 'A quick update for you.',
        ]);

        $response->assertRedirect();
        Notification::assertSentTimes(\App\Notifications\AdminMessage::class, 2);
    }

    public function test_admin_can_impersonate_and_return(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.impersonate', $user));
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        $response = $this->post(route('admin.impersonate.stop'));
        $response->assertRedirect(route('admin.users.index'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_cannot_impersonate_another_admin(): void
    {
        $admin = $this->admin();
        $otherAdmin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.users.impersonate', $otherAdmin));

        $response->assertForbidden();
    }

    public function test_export_returns_a_csv_download(): void
    {
        $admin = $this->admin();
        User::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.users.export'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_non_admin_cannot_manage_users(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
    }
}
