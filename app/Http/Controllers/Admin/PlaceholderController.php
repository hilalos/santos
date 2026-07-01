<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlaceholderController extends Controller
{
    /**
     * Display a placeholder "coming soon" page for a sidebar menu item.
     */
    public function show(string $section, string $item): View
    {
        foreach (config('admin_menu') as $menuSection) {
            if (empty($menuSection['items']) || Str::slug($menuSection['label']) !== $section) {
                continue;
            }

            foreach ($menuSection['items'] as $menuItem) {
                if (Str::slug($menuItem) === $item) {
                    return view('admin.placeholder', [
                        'sectionIcon' => $menuSection['icon'],
                        'sectionLabel' => $menuSection['label'],
                        'itemLabel' => $menuItem,
                    ]);
                }
            }
        }

        abort(404);
    }
}
