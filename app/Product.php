<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 02 Jul 2017 22:23:16 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Product
 * 
 * @property int $productID
 * @property string $productName
 * @property string $description
 * @property string $brand
 * @property float $price
 * @property int $supplierID
 * @property int $categoryID
 * @property string $gender
 * 
 * @property \App\Category $category
 * @property \App\Supplier $supplier
 * @property \Illuminate\Database\Eloquent\Collection $colors
 * @property \Illuminate\Database\Eloquent\Collection $orderdetails
 *
 * @package App
 */
class Product extends Eloquent
{
    protected $primaryKey = 'productID';
    public $timestamps = false;

    protected $casts = [
        'price' => 'float',
        'supplierID' => 'int',
        'categoryID' => 'int'
    ];

    protected $fillable = [
        'productName',
        'description',
        'brand',
        'price',
        'supplierID',
        'categoryID',
        'gender'
    ];

    public function pass($product)
    {
        $this->productName = $product->productName;
        $this->description = $product->description;
        $this->brand = $product->brand;
        $this->price = $product->price;
        $this->gender = $product->gender;
    }


    public function category()
    {
        return $this->belongsTo(\App\Category::class, 'categoryID');
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Supplier::class, 'supplierID');
    }

    public function cartproducts()
    {
        return $this->hasMany(\App\Cartproduct::class, 'productID');
    }

    public function colors()
    {
        return $this->hasMany(\App\Color::class, 'productID');
    }

    public function favourites()
    {
        return $this->hasMany(\App\Favourite::class, 'productID');
    }

    public function orderdetails()
    {
        return $this->hasMany(\App\Orderdetail::class, 'productID');
    }

}
