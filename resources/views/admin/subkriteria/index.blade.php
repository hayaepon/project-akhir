@extends('admin.layouts.app')

@section('title', 'Kriteria & Bobot')

@section('content')
  <!-- Tabel Sub Kriteria -->
  <div class="bg-white p-6 ">
        <h2 class="font-bold text-2xl mb-2">Data Sub Kriteria</h2>
        <hr class="border-t-2 border-gray-300 mb-4 w-full">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="bg-blue-800 text-white font-medium">
                    <th class="border px-4 py-2 text-left">No</th>
                    <th class="border px-4 py-2 text-left">Kriteria</th>
                    <th class="border px-4 py-2 text-left">Sub Kriteria</th>
                    <th class="border px-4 py-2 text-left">Nilai</th>
                    <th class="border px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subkriterias as $subKriteria)
                    <tr class="bg white">
                        <td class="border px-6 py-4 font-normal">{{ $loop->iteration }}</td>
                        <td class="border px-6 py-4 font-normal">{{ $subKriteria->kriteria->kriteria }}</td>
                        <td class="border px-6 py-4 font-normal">{{ $subKriteria->sub_kriteria }}</td>
                        <td class="border px-6 py-4 font-normal">{{ $subKriteria->nilai }}</td>
                        <td class="border px-6 py-4 flex space-x-2">
                            <a href="{{ route('subkriteria.edit', $subKriteria->id) }}" class="text-yellow-500 hover:underline">
                            <i class="fas fa-edit text-yellow-300"></i>
                            </a>
                            <form action="{{ route('subkriteria.destroy', $subKriteria->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">
                                <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection