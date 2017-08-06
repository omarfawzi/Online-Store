<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Aug 2017 00:46:51 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Order
 * 
 * @property int $orderID
 * @property string $region
 * @property string $address
 * @property string $date
 * 
 * @property \Illuminate\Database\Eloquent\Collection $orderdetails
 *
 * @package App
 */
class Order extends Eloquent
{
	protected $primaryKey = 'orderID';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'orderID' => 'int'
	];

	protected $fillable = [
		'region',
		'address',
		'date'
	];

	public function orderdetails()
	{
		return $this->hasMany(\App\Orderdetail::class, 'orderID');
	}
}
