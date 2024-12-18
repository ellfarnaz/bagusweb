<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Makanan;
use App\Models\Minuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function getMakanan()
    {
        try {
            $makanan = Makanan::all();
            
            return response()->json([
                'success' => true,
                'data' => $makanan
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting makanan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data makanan'
            ], 500);
        }
    }

    public function getMinuman()
    {
        $minuman = Minuman::all();
        
        return response()->json([
            'success' => true,
            'data' => $minuman
        ]);
    }
} 