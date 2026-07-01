<?php

use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlaceholderController;
use App\Http\Controllers\Admin\Users\UserBulkActionController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\Users\UserExportController;
use App\Http\Controllers\Admin\Users\UserImpersonationController;
use App\Http\Controllers\Admin\Users\UserImportController;
use App\Http\Controllers\Admin\Users\UserNoteController;
use App\Http\Controllers\Admin\Users\UserNotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    });

    // Not gated by the 'admin' middleware: while impersonating, the current
    // session user is deliberately a non-admin, so returning must stay reachable.
    Route::middleware('auth')->group(function () {
        Route::post('impersonate/stop', [UserImpersonationController::class, 'destroy'])->name('impersonate.stop');
    });

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('export', UserExportController::class)->name('export');
            Route::post('import', [UserImportController::class, 'store'])->name('import');
            Route::post('bulk', UserBulkActionController::class)->name('bulk');

            Route::patch('{user}', [UserController::class, 'update'])->name('update');
            Route::delete('{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('{user}/status', [UserController::class, 'updateStatus'])->name('status');
            Route::post('{user}/plan', [UserController::class, 'updatePlan'])->name('plan');
            Route::post('{user}/verify-email', [UserController::class, 'verifyEmail'])->name('verify-email');
            Route::post('{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
            Route::post('{user}/notify', [UserNotificationController::class, 'store'])->name('notify');
            Route::post('{user}/notes', [UserNoteController::class, 'store'])->name('notes.store');
            Route::post('{user}/impersonate', [UserImpersonationController::class, 'store'])->name('impersonate');
        });

        Route::get('{section}/{item}', [PlaceholderController::class, 'show'])->name('placeholder');
    });
});
