<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 15:49:06 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Orderdetail
 *
 * @property int $orderdetailsID
 * @property int $orderID
 * @property int $quantity
 * @property int $colorID
 * @property int $sizeID
 * @property int $productID
 * @property int $supplierID
 *
 * @property \App\Order $order
 * @property \App\Color $color
 * @property \App\Product $product
 * @property \App\Size $size
 * @property \App\Supplier $supplier
 *
 * @package App
 */
class Orderdetail extends Eloquent
{
    protected $primaryKey = 'orderdetailsID';
    public $timestamps = false;

    protected $casts = [
        'orderID' => 'int',
        'quantity' => 'int',
        'colorID' => 'int',
        'sizeID' => 'int',
        'productID' => 'int',
        'supplierID' => 'int'
    ];

    protected $fillable = [
        'orderID',
        'quantity',
        'colorID',
        'sizeID',
        'productID',
        'supplierID'
    ];

    public function order()
    {
        return $this->belongsTo(\App\Order::class, 'orderID');
    }

    public function color()
    {
        return $this->belongsTo(\App\Color::class, 'colorID');
    }

    public function product()
    {
        return $this->belongsTo(\App\Product::class, 'productID');
    }

    public function size()
    {
        return $this->belongsTo(\App\Size::class, 'sizeID');
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Supplier::class, 'supplierID');
    }
}
