<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\StoreNoteRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserNoteController extends Controller
{
    public function store(StoreNoteRequest $request, User $user): RedirectResponse
    {
        $user->notes()->create([
            'author_id' => Auth::id(),
            'body' => $request->string('body')->toString(),
        ]);

        return back()->with('success', 'Note added.');
    }
}
