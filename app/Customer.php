<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 04 Aug 2017 00:46:50 +0000.
 */

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
 * @property string $provider
 * @property int $provider_id
 * @property string $phoneNumber
 *
 * @property \Illuminate\Database\Eloquent\Collection $cartproducts
 * @property \Illuminate\Database\Eloquent\Collection $complains
 * @property \Illuminate\Database\Eloquent\Collection $favourites
 * @property \Illuminate\Database\Eloquent\Collection $feedback
 *
 * @package App
 */
class Customer extends Authenticatable
{
    use Notifiable;
    protected $primaryKey = 'customerID';

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'birthDate',
        'address',
        'password',
        'phoneNumber',
        'gender',
        'provider',
        'provider_id'
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

    public function orders()
    {
        return $this->hasMany(\App\Order::class, 'customerID');
    }
}
