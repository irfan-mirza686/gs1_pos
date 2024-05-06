<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupModule extends Model
{
    use HasFactory;

    protected $fillable = ['id','module_name','module_page'];
}
