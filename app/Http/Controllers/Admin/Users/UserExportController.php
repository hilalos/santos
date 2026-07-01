<?php

namespace App\Http\Controllers\Admin\Users;

use App\Filters\Admin\UserFilter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserExportController extends Controller
{
    public function __invoke(Request $request, UserFilter $filter): StreamedResponse
    {
        $ids = $request->input('ids', []);

        $query = $ids
            ? User::whereIn('id', $ids)
            : $filter->apply(User::query());

        $columns = ['ID', 'Name', 'Email', 'Role', 'Plan', 'Country', 'Status', 'Registered At', 'Last Login'];

        return response()->streamDownload(function () use ($query, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);

            $query->orderBy('id')->chunk(200, function ($users) use ($handle) {
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->role->label(),
                        $user->plan->label(),
                        $user->country,
                        $user->status->label(),
                        $user->created_at?->toDateTimeString(),
                        $user->last_login_at?->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        }, 'users-export-'.now()->format('Y-m-d-His').'.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
