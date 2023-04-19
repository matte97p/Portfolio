<?php

namespace App\Models;

use App\Models\Base\UsersCurrent as BaseUsersCurrent;

class UsersCurrent extends BaseUsersCurrent
{
	protected $hidden = [
		self::DELETED_AT,
		self::VERSION
	];
}
