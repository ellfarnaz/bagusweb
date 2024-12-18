<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 16px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Penjualan</h2>
        <p>Periode: {{ $tanggal_awal }} - {{ $tanggal_akhir }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="20%">Tanggal</th>
                <th width="15%">No Transaksi</th>
                <th width="25%">Customer</th>
                <th width="20%">Total</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $transaksi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $transaksi->id }}</td>
                <td>{{ $transaksi->customer_name }}</td>
                <td class="text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                <td>{{ ucfirst($transaksi->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada transaksi pada periode ini</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Total Keseluruhan:</strong></td>
                <td class="text-right" colspan="2"><strong>Rp {{ number_format($total_keseluruhan, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html> 