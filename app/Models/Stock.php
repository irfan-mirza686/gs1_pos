<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['id','type','supplier_id','productName','product_id','barcode','barcode_2','qty','price','selling_price'];

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
}
