@extends('superadmin.layouts.app')

@section('title', 'Tambah Admin')

@section('content')
    <div class="container mx-auto py-6 px-4">
        <h2 class="text-2xl font-semibold mb-4">Tambah Admin Baru</h2>

        <form method="POST" action="{{ route('manajemen_admin.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-2">Nama:</label>
                <input type="text" name="name" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Email:</label>
                <input type="email" name="email" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Password:</label>
                <input type="password" name="password" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Konfirmasi Password:</label>
                <input type="password" name="password_confirmation" class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Role:</label>
                <select name="role" class="w-full p-2 border rounded" required>
                    <option value="Super_Admin">SuperAdmin</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('manajemen_admin.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
            </div>
        </form>
    </div>
@endsection
