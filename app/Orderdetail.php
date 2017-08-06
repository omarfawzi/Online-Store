<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Aug 2017 00:46:51 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Orderdetail
 * 
 * @property int $orderID
 * @property int $productID
 * @property float $price
 * @property int $quantity
 * 
 * @property \App\Order $order
 * @property \App\Product $product
 *
 * @package App
 */
class Orderdetail extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'orderID' => 'int',
		'productID' => 'int',
		'price' => 'float',
		'quantity' => 'int'
	];

	protected $fillable = [
		'price',
		'quantity'
	];

	public function order()
	{
		return $this->belongsTo(\App\Order::class, 'orderID');
	}

	public function product()
	{
		return $this->belongsTo(\App\Product::class, 'productID');
	}
}
