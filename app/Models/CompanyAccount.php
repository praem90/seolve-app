<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAccount extends Model
{
    use HasFactory;

    public $casts = [
        'meta' => 'array'
    ];

    public $fillable = [
        'account_id'
    ];

    public $hidden = [
        'access_token',
        'meta'
    ];
}
