<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 22 Jun 2017 02:54:14 +0000.
 */

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    use Notifiable;
	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'password',
		'type',
		'remember_token'
	];
}
