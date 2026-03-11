<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Menentukan kolom mana saja yang boleh diisi secara massal (Mass Assignment)
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'date',
        'proofs',
    ];

    protected $casts = [
        'proofs' => 'array'
    ];

    // Relasi: Satu transaksi dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}