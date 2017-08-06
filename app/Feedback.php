<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Aug 2017 00:46:50 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Feedback
 * 
 * @property string $description
 * @property int $customerID
 * @property int $DManID
 * 
 * @property \App\Customer $customer
 *
 * @package App
 */
class Feedback extends Eloquent
{
	protected $table = 'feedbacks';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'customerID' => 'int',
		'DManID' => 'int'
	];

	protected $fillable = [
		'description'
	];

	public function customer()
	{
		return $this->belongsTo(\App\Customer::class, 'customerID');
	}
}
