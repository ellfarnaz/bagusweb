@extends('layout')
@section('page', 'Laporan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Transaksi</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan.cetak') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Awal</label>
                                    <input type="date" name="tanggal_awal" class="form-control" value="{{ date('Y-m-01') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Akhir</label>
                                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary form-control">
                                        Cetak Laporan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>No Transaksi</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $transaksis = $transaksis ?? collect();
                                    $total = 0; 
                                @endphp
                                @foreach($transaksis as $index => $transaksi)
                                    @php $total += $transaksi->total_harga; @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $transaksi->id }}</td>
                                        <td>{{ $transaksi->customer_name }}</td>
                                        <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                        <td>{{ ucfirst($transaksi->status) }}</td>
                                    </tr>
                                @endforeach
                                @if($transaksis->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total Keseluruhan:</strong></td>
                                    <td colspan="2"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
