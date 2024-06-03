<!DOCTYPE html>
<html lang="en">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $method }} Rankings</title>
    <style>
        body {
            font-family: Roboto, -apple-system, 'Helvetica Neue', 'Segoe UI', Arial, sans-serif;
            background-color: #727da6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 800px;
            width: 100%;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #dee5fc;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        @media (max-width: 600px) {
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $method }} Rankings</h1>
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Nama</th>
                    <th>TWK</th>
                    <th>TIU</th>
                    <th>TKP</th>
                    <th>Total Nilai</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rankings as $index => $ranking)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ranking['nama'] }}</td>
                        <td>{{ $ranking['twk'] }}</td>
                        <td>{{ $ranking['tiu'] }}</td>
                        <td>{{ $ranking['tkp'] }}</td>
                        <td>{{ $ranking['total'] }}</td>
                        {{-- Periksa apakah key 'keterangan' tersedia sebelum mencetak --}}
                        <td>{{ $ranking['keterangan'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
