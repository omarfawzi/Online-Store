<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 02 Jul 2017 22:23:16 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Supplier
 * 
 * @property int $supplierID
 * @property string $supplierName
 * @property string $phoneNumber
 * @property string $Email
 * @property string $Description
 * @property string Address
 * @property string $suppImage
 * 
 * @property \Illuminate\Database\Eloquent\Collection $products
 *
 * @package App
 */
class Supplier extends Eloquent
{
	protected $primaryKey = 'supplierID';
	public $timestamps = false;

	protected $fillable = [
		'supplierName',
		'phoneNumber',
        'Address',
		'Email',
		'suppImage',
        'Description'
	];

	public function products()
	{
		return $this->hasMany(\App\Product::class, 'supplierID');
	}

    public function pass($supplier){
        $this->supplierID = $supplier->id;
        $this->supplierName = $supplier->name;
        $this->Email = $supplier->email;
    }
    public function orderdetails()
    {
        return $this->hasMany(\App\Orderdetail::class, 'supplierID');
    }
}
