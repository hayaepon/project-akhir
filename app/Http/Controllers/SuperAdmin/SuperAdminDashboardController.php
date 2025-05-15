<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        return view('superadmin.dashboard');
    }
}
