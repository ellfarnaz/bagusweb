<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiItem extends Model
{
    protected $table = 'transaksi_item';

    protected $fillable = [
        'transaksi_id',
        'makanan_id',
        'minuman_id',
        'jumlah',
        'harga',
        'total_harga'
    ];

    protected $with = ['makanan', 'minuman'];

    // Relasi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function makanan()
    {
        return $this->belongsTo(Makanan::class);
    }

    public function minuman()
    {
        return $this->belongsTo(Minuman::class);
    }
}
