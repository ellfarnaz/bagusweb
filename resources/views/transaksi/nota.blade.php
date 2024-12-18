<!DOCTYPE html>
<html>
<head>
    <title>Nota Transaksi #{{ $transaksi->id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .nota {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #FF6F06;
        }
        .header h2 {
            color: #FF6F06;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .customer-info {
            margin-bottom: 30px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 6px;
        }
        .customer-info h3 {
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px;
            border: none;
        }
        .info-table td:first-child {
            width: 120px;
            color: #666;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #FF6F06;
            color: white;
            padding: 12px;
            text-align: left;
        }
        .items-table td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row td {
            font-weight: bold;
            background-color: #f5f5f5;
        }
        .total-amount {
            color: #FF6F06;
            font-size: 18px;
        }
        .buttons {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .btn {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-print {
            background-color: #FF6F06;
            color: white;
        }
        .btn-print:hover {
            background-color: #e65c00;
        }
        .btn-close {
            background-color: #6c757d;
            color: white;
        }
        .btn-close:hover {
            background-color: #5a6268;
        }
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .nota {
                box-shadow: none;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
            .items-table th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="nota">
        <div class="header">
            <h2>NOTA TRANSAKSI</h2>
            <p>No. Transaksi: #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p>Tanggal: {{ \Carbon\Carbon::parse($transaksi->created_at)->isoFormat('D MMMM Y HH:mm') }} WIB</p>
        </div>

        <div class="customer-info">
            <h3>Informasi Pelanggan</h3>
            <table class="info-table">
                <tr>
                    <td><strong>Nama</strong></td>
                    <td>: {{ $transaksi->customer_name }}</td>
                </tr>
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td>: {{ $transaksi->customer_alamat }}</td>
                </tr>
                <tr>
                    <td><strong>No. HP</strong></td>
                    <td>: {{ $transaksi->customer_no_hp }}</td>
                </tr>
                @if($transaksi->customer_catatan)
                <tr>
                    <td><strong>Catatan</strong></td>
                    <td>: {{ $transaksi->customer_catatan }}</td>
                </tr>
                @endif
            </table>
        </div>

        <h3 style="color: #333;">Detail Pesanan</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 40%;">Item</th>
                    <th style="width: 15%;">Jumlah</th>
                    <th style="width: 20%;">Harga</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if($item->makanan)
                            {{ $item->makanan->nama }}
                        @endif
                        @if($item->minuman)
                            {{ $item->minuman->nama }}
                        @endif
                    </td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ formatRupiah($item->harga) }}</td>
                    <td>{{ formatRupiah($item->total_harga) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" style="text-align: right;"><strong>Total Pembayaran:</strong></td>
                    <td class="total-amount">{{ formatRupiah($transaksi->total_harga) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="no-print buttons">
            <button class="btn btn-print" onclick="window.print()">Cetak Nota</button>
            <button class="btn btn-close" onclick="window.location.href='{{ route('transaksi.index') }}'">Kembali</button>
        </div>
    </div>
</body>
</html>

@php
function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}
@endphp
