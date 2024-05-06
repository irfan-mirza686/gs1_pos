<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogActivity extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'id',
        'ip',
        'subject',
        'agent',
        'url',
        'user_id',
        'username',
        'date',
        'read_status'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}