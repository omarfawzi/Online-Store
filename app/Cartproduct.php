<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 13 Aug 2017 23:17:36 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cartproduct
 *
 * @property int $cartProductID
 * @property int $productID
 * @property int $quantity
 * @property int $customerID
 * @property int $colorID
 * @property int $sizeID
 *
 * @property \App\Customer $customer
 * @property \App\Color $color
 * @property \App\Size $size
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
        'customerID' => 'int',
        'colorID' => 'int',
        'sizeID' => 'int'
    ];

    protected $fillable = [
        'productID',
        'quantity',
        'customerID',
        'colorID',
        'sizeID'
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Customer::class, 'customerID');
    }

    public function color()
    {
        return $this->belongsTo(\App\Color::class, 'colorID');
    }

    public function size()
    {
        return $this->belongsTo(\App\Size::class, 'sizeID');
    }

    public function product()
    {
        return $this->belongsTo(\App\Product::class, 'productID');
    }
}
