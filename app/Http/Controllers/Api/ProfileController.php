<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Validate request
            $request->validate([
                'nama' => 'required|string|max:255',
                'alamat' => 'nullable|string',
                'no_hp' => 'nullable|string',
                'patokan' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Update user data
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'nama' => $request->nama,
                    'alamat' => $request->alamat,
                    'no_hp' => $request->no_hp,
                    'patokan' => $request->patokan,
                ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image_path) {
                    Storage::delete('public/profile_images/' . basename($user->image_path));
                }

                // Store new image
                $imagePath = $request->file('image')->store('public/profile_images');
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['image_path' => Storage::url($imagePath)]);
            }

            // Get updated user data
            $updatedUser = DB::table('users')->find($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui',
                'data' => $updatedUser
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profile: ' . $e->getMessage()
            ], 500);
        }
    }
} 