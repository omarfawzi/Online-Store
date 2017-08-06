<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 28 Jul 2017 19:30:21 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Favourite
 * 
 * @property int $favouriteID
 * @property int $productID
 * @property int $customerID
 * 
 * @property \App\Product $product
 * @property \App\Customer $customer
 *
 * @package App
 */
class Favourite extends Eloquent
{
	protected $primaryKey = 'favouriteID';
	public $timestamps = false;

	protected $casts = [
		'productID' => 'int',
		'customerID' => 'int'
	];

	protected $fillable = [
		'productID',
		'customerID'
	];

	public function product()
	{
		return $this->belongsTo(\App\Product::class, 'productID');
	}

	public function customer()
	{
		return $this->belongsTo(\App\Customer::class, 'customerID');
	}
}
