<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'user_type',
        'parent_memberID',
        'slug',
        'have_cr',
        'cr_documentID',
        'document_number',
        'fname',
        'lname',
        'email',
        'mobile',
        'image',
        'companyID',
        'cr_number',
        'cr_activity',
        'company_name_eng',
        'company_name_arabic',
        'gcpGLNID',
        'gln',
        'gcp_expiry',
        'password',
        'code',
        'settings',
        'status',
        'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'settings' => 'array'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'user_id');
    }
}
