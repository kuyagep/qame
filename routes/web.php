<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SchoolMapController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('optimize-clear', function () {
    try {
        Artisan::call('optimize:clear');
        return '✅ Optimization cache cleared successfully!';
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});
Route::get('config-clear', function () {
    try {
        Artisan::call('config:clear');
        return '✅ Config Cleared successfully!';
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});


Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return redirect('/dashboard');
});

Route::prefix('address')->group(function () {
    Route::get('regions', [AddressController::class, 'getRegions']);
    Route::get('provinces/{regionId}', [AddressController::class, 'getProvinces']);
    Route::get('cities/{provinceId}', [AddressController::class, 'getCities']);
    Route::get('barangays/{cityId}', [AddressController::class, 'getBarangays']);
});

Route::get('/reload-captcha', [CaptchaController::class, 'reloadCaptcha'])->name('reload-captcha');


Route::get('/api/offices', function (Request $request) {
    return Office::where('department_id', $request->query('department_id'))
        ->select(['id', 'name'])
        ->orderBy('name')
        ->get();
});


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::get('/users/export-selected', [UserController::class, 'exportSelected'])->name('users.export-selected');
    Route::get('/users/export-all', [UserController::class, 'exportAll'])->name('users.export.all');
    Route::resource('users', UserController::class);
    // Explicit Role Assignment Route Hook
    Route::put('users/{user}/assign-roles', [UserController::class, 'assignRoles'])->name('users.assign-roles');
    Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');

    Route::resource('roles', RoleController::class);
    Route::get('role-permissions/{id}/edit', [RolePermissionController::class, 'edit'])->name('role-permissions.edit');
    Route::put('role-permissions/{id}', [RolePermissionController::class, 'update'])->name('role-permissions.update');
    Route::resource('permissions', PermissionController::class);

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');




    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    Route::post('notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.clear');

    Route::resource('announcements', AnnouncementController::class)->only(['index', 'store', 'destroy']);
    Route::patch('announcements/{id}/toggle', [AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle');
    Route::resource('offices', OfficeController::class)->except(['create', 'show']);
    Route::resource('departments', DepartmentController::class)->except(['create', 'show']);

    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/fetch', [EventController::class, 'fetchEvents'])->name('events.fetch');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');


    Route::post('/events/{id}/join', [EventController::class, 'toggleJoin'])
        ->name('events.toggle-join');
});

Route::get('/events/join/{code}', [EventController::class, 'joinByLink'])->name('events.join-by-link');
Route::post('/events/join/{code}/pre-register', [EventController::class, 'storePreRegistration'])->name('events.pre-register.store');

Route::middleware('auth')->group(function () {
    // Dynamically serve the authenticated user's avatar
    // Route::get('/profile/avatar', [ProfileController::class, 'avatar'])->name('profile.avatar');
    Route::get('/users/{user}/avatar', [ProfileController::class, 'showAvatar'])->name('users.avatar');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/password', [ProfileController::class, 'password'])
        ->name('profile.password');
});
Route::get('/settings/image/{key}', [SettingController::class, 'showImage'])->name('image.show');
Route::get('/school-map', [SchoolMapController::class, 'index'])->name('school-map.index');
require __DIR__ . '/auth.php';
require __DIR__ . '/dts.php';
require __DIR__ . '/icts.php';
