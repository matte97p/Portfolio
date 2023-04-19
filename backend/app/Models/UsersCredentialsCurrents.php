<?php

namespace App\Models;

use App\Traits\HasUuid;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsersCredentialsCurrents extends Authenticatable
{
    use HasUuid, HasApiTokens, HasRoles, Notifiable, SoftDeletes, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_credentials_currents';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'version',
    ];

    public static function findByPrimary($id)
    {
        return self::where('id', $id)->first();
    }

    /**
     * Find the user instance for the given username.
     *
     * @param  string $username
     * @return \App\Models\UsersCredentialsCurrents
     */
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
}
