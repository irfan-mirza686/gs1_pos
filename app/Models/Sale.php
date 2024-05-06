<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'id',
        'order_no',
        'customer_id',
        'date',
        'description',
        'items',
        'total',
        'paid_amount',
        'sale_type',
        'status',
        'user_id'
      ];
    
      protected $casts = [
        'items' => 'array'
      ];
    
      public function users()
      {
        return $this->belongsTo('App\Models\User', 'user_id');
      }
      public function customer()
      {
        return $this->belongsTo(Customer::class, 'customer_id');
      }
}
