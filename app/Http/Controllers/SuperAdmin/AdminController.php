<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Menampilkan daftar admin & superadmin
    public function index()
    {
        $admins = User::whereIn('role', ['Admin', 'Super_Admin'])->get();
        return view('superadmin.manajemen_admin.index', compact('admins'));
    }

    // Menampilkan form tambah admin
    public function create()
    {
        return view('superadmin.manajemen_admin.create');
    }

    // Menyimpan data admin baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:Admin,Super_Admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('manajemen_admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    // Menampilkan form edit admin
    public function edit($id)
    {
        $admin = User::findOrFail($id);
        return view('superadmin.manajemen_admin.edit', compact('admin'));
    }

    // Menyimpan perubahan admin (termasuk ganti password jika diisi)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:Admin,Super_Admin',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin = User::findOrFail($id);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Cek jika password diisi, maka update password juga
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        $message = 'Data admin berhasil diperbarui.';
        if ($request->filled('password')) {
            $message .= ' Password berhasil diganti.';
        }

        return redirect()->route('manajemen_admin.index')->with('success', $message);
    }

    // Menghapus data admin
    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();

        return redirect()->route('manajemen_admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}
