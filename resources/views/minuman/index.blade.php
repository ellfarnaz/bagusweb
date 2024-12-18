@extends('layout')
@section('page','Minuman')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Data Minuman</h4>
            </div>
            <div class="card-body">
                <form action="/simpan-minuman" method="post" enctype="multipart/form-data">
                    @csrf
        
                <div class="form-group">
                    <label for="">Nama Minuman</label>
                    <input type="text" class="form-control" name="nama">
                </div>
                <div class="form-group">
                    <label for="">Harga Minuman</label>
                    <input type="number" class="form-control" name="harga">
                </div>
                <div class="form-group">
                    <label for="">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi"></textarea>
                </div>
                <div class="form-group">
                    <label for="">Foto</label>
                    <input type="file" class="form-control" name="foto">
                </div>
            </div>
            <div class="card-footer">
                
                <button type="submit" class="btn btn-sm btn-warning text-center">Simpan</button>
            </form>
            </div>
        </div>
    </div>
</div>
<br><br>
<div class="row">
    @foreach($data as $item)
    <div class="col-md-4">
    <div class="card p-2" style="border-radius: 10px;">
            <div class="row">
                <div class="col-4">
                    <img src="/images/{{$item->foto}}" alt="" class="img-fluid w-100">
                </div>
                <div class="col-8">
                    <h5>{{ $item->nama }}</h5>
                    <p>{{ $item->deskripsi }}</p>
                    <p>Rp {{ $item->harga }}</p>
                </div>
                <div class="col-12">
                    <div class="row text-center">
                        <div class="col-6">
                            <a href="/hapus-minuman/{{ $item->id }}" class="btn btn-sm btn-danger">Hapus</a>
                        </div>
                        <div class="col-6">
                            <a href="/edit-minuman/{{ $item->id }}" class="btn btn-sm btn-warning">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach


    </div>
</div>

@endsection