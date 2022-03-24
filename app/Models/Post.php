<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $timestamps = [
        'scheduled_at'
    ];

    public $casts = [
        'description' => 'array',
        'accounts' => 'array',
        'attachments' => 'array',
        'response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

	public function accounts()
	{
		return $this->hasManyThrough(CompanyAccount::class, PostAccount::class, 'post_id', 'account_id', 'id', 'company_account_id');
	}

	public function postAccounts()
	{
		return $this->hasMany(PostAccount::class);
	}
}
