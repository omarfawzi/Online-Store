<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 08 Aug 2017 18:31:00 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Order
 * 
 * @property int $orderID
 * @property string $status
 * @property string $region
 * @property string $address
 * @property string $date
 * @property int $customerID
 * 
 * @property \App\Customer $customer
 * @property \Illuminate\Database\Eloquent\Collection $orderdetails
 *
 * @package App\Models
 */
class Order extends Eloquent
{
	protected $primaryKey = 'orderID';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'orderID' => 'int',
		'customerID' => 'int'
	];

	protected $fillable = [
		'status',
		'region',
		'address',
		'date',
		'customerID'
	];

	public function customer()
	{
		return $this->belongsTo(\App\Customer::class, 'customerID');
	}

	public function orderdetails()
	{
		return $this->hasMany(\App\Orderdetail::class, 'orderID');
	}
}
