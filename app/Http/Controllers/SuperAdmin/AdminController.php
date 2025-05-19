<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        return view('superadmin.manajemen_admin.index', compact('admins'));
    }
    public function create()
    {
    return view('superadmin.manajemen_admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:admins',
            'username' => 'required|unique:admins',
            'role' => 'required',
            'status' => 'required',
            
        ]);

        Admin::create($request->all());

        return redirect()->route('manajemen_admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('manajemen_admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}
