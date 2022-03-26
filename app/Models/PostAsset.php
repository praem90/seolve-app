<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Class PostAsset
 *
 * @property int $id
 * @property int $post_id
 * @property string|null $mime_type
 * @property string|null $path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Post $post
 * @property Collection|PostAccountAsset[] $post_account_assets
 *
 * @package App\Models
 */
class PostAsset extends Model
{
	protected $casts = [
		'post_id' => 'int'
	];

	protected $fillable = [
		'post_id',
		'mime_type',
		'path'
	];

	public function post(): BelongsTo
	{
		return $this->belongsTo(Post::class);
	}

	public function postAccountAssets(): HasMany
	{
		return $this->hasMany(PostAccountAsset::class);
	}

	public function isPhoto()
	{
		$imageMimeTypes = [
			'image/png',
			'image/jpg',
			'image/jpeg',
			'image/gif',
		];

		return in_array($this->mime_type, $imageMimeTypes);
	}

	public function isVideo()
	{
		$videoMimeTypes = [
			'image/mp4',
			'image/mpeg',
			'image/mpeg4',
			'image/avi',
			'image/ogg',
		];

		return in_array($this->mime_type, $videoMimeTypes);
	}

	public function getStoragePath()
	{
		return Storage::path($this->path);
	}
}
