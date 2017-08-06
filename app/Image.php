<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 02 Jul 2017 22:23:16 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Image
 * 
 * @property int $imageID
 * @property string $image
 * @property string $type
 * @property int $colorID
 * 
 * @property \App\Color $color
 *
 * @package App
 */
class Image extends Eloquent
{
	protected $primaryKey = 'imageID';
	public $timestamps = false;

	protected $casts = [
		'colorID' => 'int'
	];

	protected $fillable = [
		'image',
		'colorID',
        'type'
	];

	public function color()
	{
		return $this->belongsTo(\App\Color::class, 'colorID');
	}
}
