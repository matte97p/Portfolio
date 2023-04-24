<?php

namespace App\Models;

use App\Models\Base\UsersCurrent as BaseUsersCurrent;

class UsersCurrent extends BaseUsersCurrent
{
	protected $hidden = [
		self::DELETED_AT,
		self::VERSION
	];

    public static function findByPrimary($id)
    {
        return self::where('id', $id)->first();
    }

    public static function findByTaxId($taxid)
    {
        return self::where('taxid', $taxid)->first();
    }
}
