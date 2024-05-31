<!DOCTYPE html>
<html>
<head>
    <title>{{ $method }} Rankings</title>
</head>
<body>
    <h1>{{ $method }} Rankings</h1>
    <table border="1">
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
                    <td>{{ $ranking['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
