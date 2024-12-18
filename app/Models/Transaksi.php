<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_alamat',
        'customer_no_hp',
        'customer_catatan',
        'total_harga',
        'status'
    ];

    // Definisikan relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Definisikan relasi dengan TransaksiItem
    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }
}