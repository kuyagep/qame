<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index() //superadmin
    {
        // Fetch active announcements globally for all user roles
        $announcements = Announcement::where('is_active', true)
            ->latest()
            ->get();

        return view('dashboard.index', [
            'users' => User::count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'recentUsers' => User::latest()
                ->take(5)
                ->get(),
            'announcements' => $announcements,
        ]);
    }

    public function admin() {}


    public function staff() {}
}
