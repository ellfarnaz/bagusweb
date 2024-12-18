<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('user')
            ->where('status', 'accepted')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('laporan.index', compact('transaksis'));
    }

    public function cetak(Request $request)
    {
        try {
            $tanggal_awal = Carbon::parse($request->input('tanggal_awal'))->startOfDay();
            $tanggal_akhir = Carbon::parse($request->input('tanggal_akhir'))->endOfDay();

            $transaksis = Transaksi::with('user')
                ->where('status', 'accepted')
                ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                ->orderBy('created_at', 'desc')
                ->get();

            $total_keseluruhan = $transaksis->sum('total_harga');

            $pdf = PDF::loadView('laporan.cetak', [
                'transaksis' => $transaksis,
                'tanggal_awal' => $tanggal_awal->format('d/m/Y'),
                'tanggal_akhir' => $tanggal_akhir->format('d/m/Y'),
                'total_keseluruhan' => $total_keseluruhan
            ]);

            return $pdf->stream('laporan-penjualan.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
