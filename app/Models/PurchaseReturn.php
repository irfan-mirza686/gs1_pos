<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturn extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'id',
        'order_no',
        'supplier_id',
        'date',
        'description',
        'items',
        'total',
        'paid_amount',
        'purchase_type',
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

      public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
      }
}
