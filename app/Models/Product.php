<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'id',
    'name',
    'slug',
    'brand',
    'unit_id',
    'description',
    'user_id',
    'status'
  ];

  public function user()
  {
    return $this->belongsTo('App\Models\User', 'user_id');
  }

  public function items()
  {
    return $this->hasMany(Stock::class, 'product_id');
  }
}