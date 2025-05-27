@extends('superadmin.layouts.app')

@section('title', 'Edit Admin')

@section('content')
<div class="container mx-auto py-6 px-4">
    <h2 class="text-2xl font-semibold mb-4">Edit Admin</h2>

    <form method="POST" action="{{ route('manajemen_admin.update', $admin->id) }}" autocomplete="off">
        @csrf
        @method('PUT')

        <!-- Dummy input untuk jebakan autofill -->
        <input type="text" name="fakeusernameremembered" style="display:none">
        <input type="password" name="fakepasswordremembered" style="display:none">

        <div class="mb-4">
            <label class="block mb-2">Nama:</label>
            <input type="text" name="name" value="{{ $admin->name }}" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block mb-2">Email:</label>
            <input type="email" name="email" value="{{ $admin->email }}" class="w-full p-2 border rounded" required autocomplete="nope">
        </div>

        <div class="mb-4">
            <label class="block mb-2">Role:</label>
            <select name="role" class="w-full p-2 border rounded" required>
                <option value="Super_Admin" {{ $admin->role == 'Super_Admin' ? 'selected' : '' }}>SuperAdmin</option>
                <option value="Admin" {{ $admin->role == 'Admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2">Password Baru:</label>
            <input type="password" name="password" class="w-full p-2 border rounded" autocomplete="new-password" placeholder="Biarkan kosong jika tidak ingin mengubah password">
        </div>

        <div class="mb-4">
            <label class="block mb-2">Konfirmasi Password Baru:</label>
            <input type="password" name="password_confirmation" class="w-full p-2 border rounded" autocomplete="new-password" placeholder="Ulangi password baru">
        </div>

        <div class="flex gap-4">
            <a href="{{ route('manajemen_admin.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
