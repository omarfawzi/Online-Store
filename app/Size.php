<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 02 Jul 2017 22:23:16 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Size
 * 
 * @property int $sizeID
 * @property string $size
 * 
 * @property \Illuminate\Database\Eloquent\Collection $colors
 *
 * @package App
 */
class Size extends Eloquent
{
	protected $primaryKey = 'sizeID';
	public $timestamps = false;

	protected $fillable = [
		'size'
	];

	public function colors()
	{
		return $this->belongsToMany(\App\Color::class, 'colorsizes', 'sizeID', 'colorID')
					->withPivot('availableUnits');
	}

}
