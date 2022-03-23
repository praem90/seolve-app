<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class PostAccount
 *
 * @property int $id
 * @property int $post_id
 * @property int $company_account_id
 * @property string|null $social_media_post_id
 * @property string|null $status
 * @property array|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property CompanyAccount $company_account
 * @property Post $post
 * @property Collection|PostAccountAsset[] $post_account_assets
 *
 * @package App\Models
 */
class PostAccount extends Model
{
	protected $table = 'post_accounts';

	protected $casts = [
		'post_id' => 'int',
		'company_account_id' => 'int',
		'meta' => 'json'
	];

	protected $fillable = [
		'post_id',
		'company_account_id',
		'social_media_post_id',
		'status',
		'meta'
	];

	public function companyAccount(): BelongsTo
	{
		return $this->belongsTo(CompanyAccount::class);
	}

	public function post(): BelongsTo
	{
		return $this->belongsTo(Post::class);
	}

	public function postAccountAssets(): HasMany
	{
		return $this->hasMany(PostAccountAsset::class);
	}
}
