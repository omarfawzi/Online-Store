<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 02 Jul 2017 22:23:15 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Category
 * 
 * @property int $categoryID
 * @property string $categoryName
 * 
 * @property \Illuminate\Database\Eloquent\Collection $products
 *
 * @package App
 */
class Category extends Eloquent
{
	protected $primaryKey = 'categoryID';
	public $timestamps = false;

	protected $fillable = [
		'categoryName'
	];

	public function products()
	{
		return $this->hasMany(\App\Product::class, 'categoryID');
	}

    public function pass($request){
        $this->categoryName = $request->categoryName;
    }

}
