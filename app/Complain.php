<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Aug 2017 00:46:50 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Complain
 * 
 * @property int $complainID
 * @property string $description
 * @property int $customerID
 * 
 * @property \App\Customer $customer
 *
 * @package App
 */
class Complain extends Eloquent
{
	protected $primaryKey = 'complainID';
	public $timestamps = false;

	protected $casts = [
		'customerID' => 'int'
	];

	protected $fillable = [
		'description',
		'customerID'
	];

	public function customer()
	{
		return $this->belongsTo(\App\Customer::class, 'customerID');
	}
}
