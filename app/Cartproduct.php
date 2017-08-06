<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Aug 2017 00:46:50 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cartproduct
 * 
 * @property int $productID
 * @property int $quantity
 * @property int $sizeID
 * @property int $colorID
 * @property int $customerID
 * 
 * @property \App\Customer $customer
 * @property \App\Product $product
 *
 * @package App
 */
class Cartproduct extends Eloquent
{
    protected $primaryKey = 'cartProductID';
    public $timestamps = false;

	protected $casts = [
		'productID' => 'int',
		'quantity' => 'int',
		'sizeID' => 'int',
		'colorID' => 'int',
		'customerID' => 'int'
	];

	protected $fillable = [
		'quantity',
		'sizeID',
		'colorID'
	];

	public function customer()
	{
		return $this->belongsTo(\App\Customer::class, 'customerID');
	}

	public function product()
	{
		return $this->belongsTo(\App\Product::class, 'productID');
	}
}
