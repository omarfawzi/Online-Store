<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 13 Jul 2017 15:03:27 +0000.
 */

namespace App;
use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Notification
 * 
 * @property int $id
 * @property string $data
 * @property string $url
 * @property bool $seen
 * @property int $userID
 * @property int $colorID
 * @property string $timestamp
 * @package App
 */
class Notification extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'seen' => 'bool',
		'userID' => 'int'
	];

	protected $fillable = [
		'data',
		'url',
		'seen',
		'userID',
        'colorID'
	];

}
