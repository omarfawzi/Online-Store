<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Aug 2017 00:46:50 +0000.
 */

namespace App;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Customer
 *
 * @property int $customerID
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $birthDate
 * @property string $address
 * @property string $gender
 * @property string $password
 * @property string $phoneNumber
 *
 * @property \Illuminate\Database\Eloquent\Collection $cartproducts
 * @property \Illuminate\Database\Eloquent\Collection $complains
 * @property \Illuminate\Database\Eloquent\Collection $favourites
 * @property \Illuminate\Database\Eloquent\Collection $feedback
 *
 * @package App
 */
class Customer extends Eloquent
{
    protected $primaryKey = 'customerID';
    public $timestamps = false;

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'birthDate',
        'address',
        'password',
        'phoneNumber',
        'gender'
    ];

    public function cartproducts()
    {
        return $this->hasMany(\App\Cartproduct::class, 'customerID');
    }

    public function complains()
    {
        return $this->hasMany(\App\Complain::class, 'customerID');
    }

    public function favourites()
    {
        return $this->hasMany(\App\Favourite::class, 'customerID');
    }

    public function feedback()
    {
        return $this->hasMany(\App\Feedback::class, 'customerID');
    }
}
