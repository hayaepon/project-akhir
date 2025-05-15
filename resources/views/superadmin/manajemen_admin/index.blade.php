@extends('superadmin.layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
<div class="container mx-auto py-4 px-6 h-screen">
    <div class="flex justify-between items-center mb-4">
        <h4 class="text-2xl font-bold mb-4">Daftar Data User</h4>
        <!-- Button to trigger modal for adding a new admin -->
        <button id="addAdminBtn" class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-600">+ Tambah</button>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full table-auto" id="adminTable">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Username</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal to Add New Admin (hidden initially) -->
<div id="addAdminModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h3 class="text-lg font-semibold mb-4">Tambah Admin</h3>
        <form id="addAdminForm">
            <div class="mb-4">
                <label class="block mb-2">Nama:</label>
                <input type="text" id="nama" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2">Email:</label>
                <input type="email" id="email" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2">Username:</label>
                <input type="text" id="username" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2">Role:</label>
                <select id="role" class="w-full p-2 border border-gray-300 rounded-lg">
                    <option value="SuperAdmin">SuperAdmin</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-2">Status:</label>
                <select id="status" class="w-full p-2 border border-gray-300 rounded-lg">
                    <option value="Aktif">Aktif</option>
                    <option value="Non-Aktif">Non-Aktif</option>
                </select>
            </div>
            <div class="flex justify-between">
                <button type="button" id="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
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
        <td class="px-4 py-2">${table.rows.length}</td>
        <td class="px-4 py-2">${nama}</td>
        <td class="px-4 py-2">${email}</td>
        <td class="px-4 py-2">${username}</td>
        <td class="px-4 py-2">${role}</td>
        <td class="px-4 py-2">${status}</td>
        <td class="px-4 py-2">
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
