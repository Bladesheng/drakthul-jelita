<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $color
 *
 * @mixin Eloquent
 */
class WowClass extends Model
{
	public function screenshot(): HasMany
	{
		return $this->hasMany(Screenshot::class);
	}
}
