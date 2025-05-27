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
            background-color: #1E40AF; /* Biru tua */
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
                <th>Kriteria 1</th>
                <th>Kriteria 2</th>
                <th>Kriteria 3</th>
                <th>Kriteria 4</th>
                <th>Hasil</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilSeleksi as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data->nama_calon_penerima }}</td>
                <td>{{ $data->nilai_kriteria1 }}</td>
                <td>{{ $data->nilai_kriteria2 }}</td>
                <td>{{ $data->nilai_kriteria3 }}</td>
                <td>{{ $data->nilai_kriteria4 }}</td>
                <td>{{ $data->hasil }}</td>
                <td>{{ $data->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
