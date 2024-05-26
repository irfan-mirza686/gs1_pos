<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_no',
        'gln',
        'date',
        'time',
        'user_id',
        'status',
        'items'
      ];

      protected $casts = [
        'items' => 'array'
      ];

}
