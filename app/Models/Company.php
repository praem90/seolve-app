<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public $fillable = [
        'name'
    ];

    public $appends = [
        'created_at_display'
    ];

    public function accounts()
    {
        return $this->hasMany(CompanyAccount::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtDisplayAttribute()
    {
        return $this->created_at->toDatetimeString();
    }
}
