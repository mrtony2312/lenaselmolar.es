<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'number',
        'email',
        'status',
        'products_total',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'products_total' => 'decimal:2',
    ];
}
