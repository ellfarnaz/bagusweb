<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\Makanan;
use App\Models\Minuman;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $makanan = Makanan::all();
        $minuman = Minuman::all();
        return view('transaksi.create', compact('makanan', 'minuman'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            Log::info('Request data:', $request->all());
            $items = json_decode($request->items, true);
            Log::info('Decoded items:', ['items' => $items]);
            
            $userId = auth()->check() ? auth()->id() : $request->user_id;
            
            $transaksi = Transaksi::create([
                'user_id' => $userId,
                'customer_name' => $request->customer_name,
                'customer_alamat' => $request->customer_alamat,
                'customer_no_hp' => $request->customer_no_hp,
                'customer_catatan' => $request->customer_catatan,
                'total_harga' => $request->total_harga,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($items as $item) {
                TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'makanan_id' => $item['makanan_id'],
                    'minuman_id' => $item['minuman_id'],
                    'jumlah' => 1,
                    'harga' => $item['harga'],
                    'total_harga' => $item['harga']
                ]);
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil dibuat',
                    'data' => $transaksi->load('items')
                ]);
            }

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating transaction: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $transaksi = Transaksi::with(['items.makanan', 'items.minuman', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transaksi.index', compact('transaksi'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['items.makanan', 'items.minuman', 'user'])
            ->findOrFail($id);

        return view('transaksi.show', compact('transaksi'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = $request->status;
        $transaksi->save();

        return redirect()->back()->with('success', 'Status transaksi berhasil diupdate');
    }

    public function accept($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->status = 'accepted';
            $transaksi->save();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diterima',
                'data' => $transaksi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menerima transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->status = 'rejected';
            $transaksi->save();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil ditolak',
                'data' => $transaksi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cetakNota($id)
    {
        try {
            $transaksi = Transaksi::with(['items.makanan', 'items.minuman', 'user'])
                ->findOrFail($id);

            return view('transaksi.nota', compact('transaksi'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencetak nota: ' . $e->getMessage());
        }
    }

    public function nota($id)
    {
        try {
            $transaksi = Transaksi::with(['items.makanan', 'items.minuman', 'user'])
                ->findOrFail($id);

            return view('transaksi.nota', compact('transaksi'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencetak nota: ' . $e->getMessage());
        }
    }
}