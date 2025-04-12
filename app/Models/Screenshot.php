<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $path
 * @property string $mime_type
 * @property int $size
 * @property string $wow_name
 * @property int $wow_class_id
 *
 * @mixin Eloquent
 */
class Screenshot extends Model
{
	public function wowClass(): BelongsTo
	{
		return $this->belongsTo(WowClass::class);
	}
}
