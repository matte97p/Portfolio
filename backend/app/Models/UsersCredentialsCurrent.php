<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
// use Illuminate\Notifications\Notifiable;
use App\Models\Base\UsersCredentialsCurrent as BaseUsersCredentialsCurrent;

class UsersCredentialsCurrent extends BaseUsersCredentialsCurrent
{
    use HasApiTokens, HasRoles, Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

	protected $hidden = [
		self::PASSWORD,
		self::REMEMBER_TOKEN,
		self::DELETED_AT,
		self::VERSION
	];

    public static function findByPrimary($id)
    {
        return self::where('id', $id)->first();
    }

    /**
     * Find the user instance for the given username.
     *
     * @param  string $username
     * @return \App\Models\UsersCredentialsCurrent
     */
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
}
