@extends('superadmin.layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
<div class="container mx-auto py-4 px-6 h-screen">
    <div class="flex justify-between items-center mb-4">
        <h4 class="text-2xl font-bold mb-4">Daftar Data User</h4>
        <!-- Button to trigger modal for adding a new admin -->
        <a href="{{ route('manajemen_admin.create') }}"
   class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-600">
   + Tambah
</a>

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
<!-- Modal Add Admin -->
<div id="addAdminModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg relative">
    <h2 class="text-xl font-semibold mb-4">Input Data User</h2>
    <form action="{{ route('manajemen_admin.store') }}" method="POST">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="nama" class="block text-sm font-medium">Nama</label>
          <input type="text" name="nama" id="nama" class="mt-1 block w-full border rounded-lg px-3 py-2" required>
        </div>
        <div>
          <label for="email" class="block text-sm font-medium">Email</label>
          <input type="email" name="email" id="email" class="mt-1 block w-full border rounded-lg px-3 py-2" required>
        </div>
        <div>
          <label for="username" class="block text-sm font-medium">Username</label>
          <input type="text" name="username" id="username" class="mt-1 block w-full border rounded-lg px-3 py-2" required>
        </div>
        <div>
          <label for="role" class="block text-sm font-medium">Role</label>
          <select name="role" id="role" class="mt-1 block w-full border rounded-lg px-3 py-2" required>
            <option value="Admin">Admin</option>
            <option value="SuperAdmin">SuperAdmin</option>
          </select>
        </div>
        <div>
          <label for="status" class="block text-sm font-medium">Status</label>
          <select name="status" id="status" class="mt-1 block w-full border rounded-lg px-3 py-2" required>
            <option value="Aktif">Aktif</option>
            <option value="Non-Aktif">Non-Aktif</option>
          </select>
        </div>
        <div>
          <label for="password" class="block text-sm font-medium">Password</label>
          <input type="password" name="password" id="password" class="mt-1 block w-full border rounded-lg px-3 py-2" required>
        </div>
      </div>

      <div class="mt-6 flex justify-end space-x-2">
        <button type="button" id="closeModal" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Batal</button>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Simpan</button>
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
