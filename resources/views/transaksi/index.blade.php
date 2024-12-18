@extends('layout')
@section('page','Transaksi')
@section('content')

@php
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi</title>
</head>
<body>
    @if(session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif
    @if(session('created'))
        <div class="alert alert-warning">
            {{ session('created') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary mb-5">Create Transaksi</a>

    <div class="card">
        <div class="card-header">
            <h4>Data Transaksi</h4>
        </div>
        
    <table class="table table-striped">
        <thead>
            <tr class="text-center">
                <th scope="col">Nomor</th>
                <th scope="col">Customer Name</th>
                <th scope="col">Customer Alamat</th>
                <th scope="col">Customer No Hp</th>
                <th scope="col">Total Bayar</th>
                <th scope="col">Customer Catatan</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $index => $transaksi)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaksi->customer_name }}</td>
                    <td>{{ $transaksi->customer_alamat }}</td>
                    <td>{{ $transaksi->customer_no_hp }}</td>
                    <td>{{ formatRupiah($transaksi->total_harga) }}</td>
                    <td>{{ $transaksi->customer_catatan }}</td>
                    <td>
                        @if($transaksi->status == 'pending')
                            <span class="badge badge-warning" style="text-transform: uppercase;">{{ $transaksi->status }}</span>
                        @elseif($transaksi->status == 'rejected')
                            <span class="badge badge-danger" style="text-transform: uppercase;">{{ $transaksi->status }}</span>
                        @elseif($transaksi->status == 'accepted')
                            <span class="badge badge-success" style="text-transform: uppercase;">{{ $transaksi->status }}</span>
                        @else
                            <span class="badge badge-secondary" style="text-transform: uppercase;">{{ $transaksi->status }}</span>
                        @endif
                    </td>
                    

                    <td>
                        @if($transaksi->status == 'pending')
                            <button id="accepted" data-toggle="modal" data-target="#detailModal{{ $transaksi->id }}" class="btn-success">Accept</button>
                            <button id="rejected" data-toggle="modal" data-target="#detailModalReject{{ $transaksi->id }}" class="btn-danger">Rejected</button>

                                <!-- Modal -->
                                <div class="modal fade" id="detailModal{{ $transaksi->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #FF6F06;">
                                                <h5 class="modal-title text-white" id="detailModalLabel">Detail Transaksi</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Customer Name:</strong>
                                                        <p>{{ $transaksi->customer_name }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Customer Alamat:</strong>
                                                        <p>{{ $transaksi->customer_alamat }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Customer No HP:</strong>
                                                        <p>{{ $transaksi->customer_no_hp }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Customer Catatan:</strong>
                                                        <p>{{ $transaksi->customer_catatan }}</p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h5>Detail Items:</h5>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">No</th>
                                                            <th scope="col">Makanan</th>
                                                            <th scope="col">Minuman</th>
                                                            <th scope="col">Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($transaksi->items as $index => $item)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $item->makanan->nama ?? '-' }}</td>
                                                                <td>{{ $item->minuman->nama ?? '-' }}</td>
                                                                <td>{{ formatRupiah(($item->makanan->harga ?? 0) + ($item->minuman->harga ?? 0)) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                                            <td class="text-center">{{ formatRupiah($transaksi->total_harga) }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-secondary" data-dismiss="modal">Close</button>
                                                <form onsubmit="return handleAccept(event, {{ $transaksi->id }})" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-success">Accepted</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- rejected --}}
                                <div class="modal fade" id="detailModalReject{{ $transaksi->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #FF6F06;">
                                                <h5 class="modal-title text-white" id="detailModalLabel">Detail Transaksi</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Customer Name:</strong>
                                                        <p>{{ $transaksi->customer_name }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Customer Alamat:</strong>
                                                        <p>{{ $transaksi->customer_alamat }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Customer No HP:</strong>
                                                        <p>{{ $transaksi->customer_no_hp }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Customer Catatan:</strong>
                                                        <p>{{ $transaksi->customer_catatan }}</p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h5>Detail Items:</h5>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">No</th>
                                                            <th scope="col">Makanan</th>
                                                            <th scope="col">Minuman</th>
                                                            <th scope="col">Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($transaksi->items as $index => $item)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $item->makanan->nama ?? '-' }}</td>
                                                                <td>{{ $item->minuman->nama ?? '-' }}</td>
                                                                <td>{{ formatRupiah(($item->makanan->harga ?? 0) + ($item->minuman->harga ?? 0)) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                                            <td class="text-center">{{ formatRupiah($transaksi->total_harga) }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-secondary" data-dismiss="modal">Close</button>
                                                <form action="{{ route('transaksi.reject', $transaksi) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-danger">Rejected</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        @elseif($transaksi->status == 'accepted')
                            <form action="{{ route('transaksi.cetak', $transaksi->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-info">Cetak Nota</button>
                            </form>
                            
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</body>
</html>

@endsection

<script>
async function handleAccept(event, id) {
    event.preventDefault();
    
    try {
        const response = await fetch(`/transaksi/${id}/accept`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            $(`#detailModal${id}`).modal('hide');
            location.reload();
        } else {
            alert('Gagal menerima transaksi');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    }
    
    return false;
}
</script>