<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 02 Jul 2017 22:23:15 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Colorsize
 * 
 * @property int $sizeID
 * @property int $colorID
 * @property string $availableUnits
 * 
 * @property \App\Color $color
 * @property \App\Size $size
 *
 * @package App
 */
class Colorsize extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'sizeID' => 'int',
		'colorID' => 'int'
	];

	protected $fillable = [
		'availableUnits'
	];

	public function color()
	{
		return $this->belongsTo(\App\Color::class, 'colorID');
	}

	public function size()
	{
		return $this->belongsTo(\App\Size::class, 'sizeID');
	}
}
