<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransaksiController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Validasi request
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'customer_name' => 'required|string',
                'customer_alamat' => 'required|string',
                'customer_no_hp' => 'required|string',
                'customer_catatan' => 'nullable|string',
                'total_harga' => 'required|numeric',
                'items' => 'required|array',
                'items.*.id' => 'required|numeric',
                'items.*.type' => 'required|in:makanan,minuman',
                'items.*.jumlah' => 'required|numeric|min:1',
                'items.*.harga' => 'required|numeric',
                'items.*.total_harga' => 'required|numeric'
            ]);

            // Buat transaksi
            $transaksi = Transaksi::create([
                'user_id' => $validated['user_id'],
                'customer_name' => $validated['customer_name'],
                'customer_alamat' => $validated['customer_alamat'],
                'customer_no_hp' => $validated['customer_no_hp'],
                'customer_catatan' => $validated['customer_catatan'] ?? '',
                'total_harga' => $validated['total_harga'],
                'status' => 'pending'
            ]);

            // Simpan items transaksi
            foreach ($validated['items'] as $item) {
                TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'makanan_id' => $item['type'] === 'makanan' ? $item['id'] : null,
                    'minuman_id' => $item['type'] === 'minuman' ? $item['id'] : null,
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'total_harga' => $item['total_harga']
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'data' => $transaksi->load('items')
            ]);

        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $transaksi = Transaksi::with(['items.makanan', 'items.minuman'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['items.makanan', 'items.minuman'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }
} 