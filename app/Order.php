<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 16 Aug 2017 04:41:27 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Order
 *
 * @property int $orderID
 * @property int $status
 * @property string $address
 * @property string $date
 * @property string $name
 * @property int $customerID
 * @property string $phone
 *
 * @property \App\Customer $customer
 * @property \Illuminate\Database\Eloquent\Collection $orderdetails
 *
 * @package App
 */
class Order extends Eloquent
{
    protected $primaryKey = 'orderID';
    public $timestamps = false;

    protected $casts = [
        'status' => 'int',
        'customerID' => 'int'
    ];

    protected $fillable = [
        'status',
        'address',
        'name',
        'date',
        'customerID',
        'phone'
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
