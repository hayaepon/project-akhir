<!DOCTYPE html>
<html>
<head>
    <title>Hasil Seleksi</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            background: #f4f4f4;
        }
        .container {
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            width: 100%;
        }
        h2 {
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        th, td {
            border: 1px solid #1e3a8a;
            padding: 8px 6px;
            text-align: left;
        }
        th {
            background: #1e3a8a;
            color: #fff;
            font-weight: normal;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hasil Seleksi</h2>
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
    </div>
</body>
</html>
