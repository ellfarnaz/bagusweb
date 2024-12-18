<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        /* Gaya CSS untuk laporan PDF */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        table th {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Laporan Transaksi</h1>
    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
            <tbody>
                @php
                    // Mengambil data transaksi hanya dengan status 'accepted'
                    $laporans = \App\Models\Transaksi::where('status', 'accepted')->get();
                    $totalKeseluruhan = $laporans->sum('total');
                @endphp
                @foreach($laporans as $laporan)
                <tr>
                    <td>{{ $laporan->id }}</td>
                    <td>{{ $laporan->customer_name }}</td>
                    <td>{{ number_format($laporan->total, 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="text-right"><strong>Total Keseluruhan</strong></td>
                    <td><strong>{{ number_format($totalKeseluruhan, 2) }}</strong></td>
                </tr>
            </tbody>
    </table>
</body>
</html>
