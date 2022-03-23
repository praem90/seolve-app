<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PostAccountAsset
 *
 * @property int $id
 * @property int $post_account_id
 * @property int $post_asset_id
 * @property string|null $social_media_asset_id
 * @property string|null $url
 * @property string|null $status
 * @property array|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property PostAccount $post_account
 * @property PostAsset $post_asset
 *
 * @package App\Models
 */
class PostAccountAsset extends Model
{
	protected $casts = [
		'post_account_id' => 'int',
		'post_asset_id' => 'int',
		'meta' => 'json'
	];

	protected $fillable = [
		'post_account_id',
		'post_asset_id',
		'social_media_asset_id',
		'url',
		'status',
		'meta'
	];

	public function postAccount(): BelongsTo
	{
		return $this->belongsTo(PostAccount::class);
	}

	public function postAsset(): BelongsTo
	{
		return $this->belongsTo(PostAsset::class);
	}
}
