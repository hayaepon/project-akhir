@extends('superadmin.layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
<div class="container mx-auto py-4 px-6 h-screen">
    <div class="flex justify-between items-center mb-4">
        <h4 class="text-2xl font-medium text-[22px]">Daftar Data User</h4>
        <a href="{{ route('manajemen_admin.create') }}"
            class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-600">
            + Tambah
        </a>
    </div>
    <hr class="border-t-2 border-gray-300 mb-4 w-full">

    <div class="overflow-x-auto bg-white shadow-md">
        <table class="min-w-full table-auto" id="adminTable">
            <thead class="bg-blue-800 text-white font-medium">
                <tr>
                    <th class="border px-4 py-2 text-left font-normal">No</th>
                    <th class="border px-4 py-2 text-left font-normal">Nama</th>
                    <th class="border px-4 py-2 text-left font-normal">Email</th>
                    <th class="border px-4 py-2 text-left font-normal">Role</th>
                    <th class="border px-4 py-2 text-left font-normal">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $index => $admin)
                <tr>
                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2">{{ $admin->name }}</td>
                    <td class="border px-4 py-2">{{ $admin->email }}</td>
                    <td class="border px-4 py-2">{{ $admin->role }}</td>
                    <td class="border px-4 py-2">
                        <div class="flex items-center">
                            <a href="{{ route('manajemen_admin.edit', $admin->id) }}" class="text-yellow-400 flex items-center text-xl">
                                <i class="fas fa-edit"></i>
                            </a>
                            <span class="mx-2 text-gray-400 text-xl flex items-center select-none" style="line-height:1;">|</span>
                            <form action="{{ route('manajemen_admin.destroy', $admin->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 flex items-center text-xl">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>

                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


@section('scripts')
<script>
    // Open modal
    document.getElementById('addAdminBtn').addEventListener('click', function() {
        document.getElementById('addAdminModal').classList.remove('hidden');
    });

    // Close modal
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('addAdminModal').classList.add('hidden');
    });

    // Handle form submission to add new row to the table
    document.getElementById('addAdminForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const table = document.getElementById('adminTable').getElementsByTagName('tbody')[0];

        // Get input values
        const nama = document.getElementById('nama').value;
        const email = document.getElementById('email').value;
        const username = document.getElementById('username').value;
        const role = document.getElementById('role').value;
        const status = document.getElementById('status').value;

        // Create a new row and cells
        const newRow = table.insertRow();
        newRow.innerHTML = `
        <td class="border px-4 py-2">${table.rows.length}</td>
        <td class="border px-4 py-2">${nama}</td>
        <td class="border px-4 py-2">${email}</td>
        <td class="border px-4 py-2">${username}</td>
        <td class="border px-4 py-2">${role}</td>
        <td class="border px-4 py-2">${status}</td>
        <td class="border px-4 py-2">
            <a href="#" class="bg-yellow-500 text-white px-2 py-1 rounded-lg shadow-sm hover:bg-yellow-600">Edit</a>
            <a href="#" class="bg-red-500 text-white px-2 py-1 rounded-lg shadow-sm hover:bg-red-600">Hapus</a>
        </td>
    `;

        // Close the modal
        document.getElementById('addAdminModal').classList.add('hidden');

        // Clear the form fields
        document.getElementById('addAdminForm').reset();
    });
</script>
@endsection