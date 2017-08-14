<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 02 Jul 2017 22:23:15 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Color
 * 
 * @property string $colorcode
 * @property int $colorID
 * @property int $productID
 * @property int $productStatus
 * @property \App\Product $product
 * @property \Illuminate\Database\Eloquent\Collection $sizes
 * @property \Illuminate\Database\Eloquent\Collection $images
 *
 * @package App
 */
class Color extends Eloquent
{
	public $timestamps = false;
    protected $primaryKey = 'colorID';

    protected $casts = [
		'productID' => 'int'
	];

	protected $fillable = [
		'colorcode',
        'productStatus'
	];

	public function product()
	{
		return $this->belongsTo(\App\Product::class, 'productID');
	}
    public function cartproducts()
    {
        return $this->hasMany(\App\Cartproduct::class, 'colorID');
    }

	public function sizes()
	{
		return $this->belongsToMany(\App\Size::class, 'colorsizes', 'colorID', 'sizeID')
					->withPivot('availableUnits');
	}

	public function images()
	{
		return $this->hasMany(\App\Image::class, 'colorID');
	}

}
