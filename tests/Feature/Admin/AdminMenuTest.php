<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_view_every_sidebar_menu_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        foreach (config('admin_menu') as $section) {
            if (empty($section['items'])) {
                continue;
            }

            $sectionSlug = Str::slug($section['label']);

            foreach ($section['items'] as $item) {
                if (! is_string($item)) {
                    continue;
                }

                $itemSlug = Str::slug($item);

                $response = $this->actingAs($admin)->get("/admin/{$sectionSlug}/{$itemSlug}");

                $response->assertStatus(200);
                $response->assertSee($item);
            }
        }
    }

    public function test_unknown_admin_menu_page_returns_404(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/does-not/exist');

        $response->assertNotFound();
    }
}
