<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Seleksi</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
        .judul {
            font-size: 18px;
            font-weight: bold;
            color:rgb(44, 44, 45);
            margin-bottom: 16px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 6px;
            text-align: left;
        }
        th {
            background-color: #1E40AF;
            color: #fff;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f1f5f9;
        }
    </style>
</head>
<body>
    <div class="judul">Hasil Seleksi Beasiswa</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Calon Penerima</th>
                @foreach ($kriterias as $kriteria)
                    <th>{{ $kriteria->kriteria }}</th>
                @endforeach
                <th>Hasil</th>
                <th>Ranking</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilSeleksi as $index => $data)
                @php
                    $nilaiKriteria = json_decode($data->nilai_kriteria, true);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->calonPenerima->nama_calon_penerima ?? '-' }}</td>
                    @foreach ($kriterias as $kriteria)
                        <td>{{ $nilaiKriteria[$kriteria->id] ?? 0 }}</td>
                    @endforeach
                    <td>{{ $data->hasil }}</td>
                    <td>{{ $index + 1 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
