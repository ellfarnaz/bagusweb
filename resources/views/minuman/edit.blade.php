@extends('layout')
@section('page','Minuman')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Edit Data Minuman</h4>
            </div>
            <div class="card-body">
                <form action="/update-minuman/{{ $data->id }}" method="post" enctype="multipart/form-data">
                    @csrf
        
                <div class="form-group">
                    <label for="">Nama Minuman</label>
                    <input type="text" class="form-control" name="nama" value="{{ $data->nama }}">
                </div>
                <div class="form-group">
                    <label for="">Harga Minuman</label>
                    <input type="number" class="form-control" name="harga" value="{{ $data->harga }}">
                </div>
                <div class="form-group">
                    <label for="">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi">{{ $data->deskripsi }}</textarea>
                </div>
                <div class="form-group">
                    <label for="">Foto</label>
                    <input type="file" class="form-control" name="foto">
                </div>
            </div>
            <div class="card-footer">
                
                <button type="submit" class="btn btn-sm btn-warning text-center">Perbarui</button>
            </form>
            </div>
        </div>
    </div>
</div>
<br><br>
@endsection