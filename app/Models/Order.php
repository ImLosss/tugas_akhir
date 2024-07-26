<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_meja',
        'profit',
        'total',
        'status',
        'kasir',
        'status_id',
        'pembayaran',
        'customer_name',
        'partner',
        'partner_profit',
        'partner_total',
        'user_id'
    ];

    public function carts() {
        return $this->hasMany(Cart::class, 'order_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }
}
