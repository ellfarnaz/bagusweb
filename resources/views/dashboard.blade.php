@extends('layout')
@section('page','Dashboard')
@section('content')
@php
  $totalMakanan = \App\Models\Makanan::count();
  $totalMinuman = \App\Models\Minuman::count();
  $totalTransaksi = \App\Models\Transaksi::where('status', 'pending')->count();
@endphp
<div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ $totalMakanan }}</h3>

                <p>Makanan</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="/makanan" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right nav-link"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ $totalMinuman }}<sup style="font-size: 20px"></sup></h3>

                <p>Minuman</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="/minuman" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right nav-link"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ $totalTransaksi }}</h3>

                <p>Transaksi Pending</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="/transaksi" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right nav-link"></i></a>
            </div>
          </div>
          <!-- ./col -->

        </div>
@endsection