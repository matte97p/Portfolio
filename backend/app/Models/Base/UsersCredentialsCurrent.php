<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\UsersContactsInfo;
use App\Models\UsersContactsInfoCurrent;
use App\Models\UsersContactsInfoHistory;
use App\Models\UsersCredential;
use App\Models\UsersCredentialsHistory;
use App\Models\UsersCurrent;
use App\Traits\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UsersCredentialsCurrent
 *
 * @property string $id
 * @property uuid $user_id
 * @property string $username
 * @property string $password
 * @property string|null $remember_token
 * @property uuid $staff_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property uuid|null $users_credentials_id
 * @property int $version
 *
 * @property UsersCurrent $users_current
 * @property UsersCredentialsCurrent|null $users_credentials_current
 * @property Collection|UsersCredential[] $users_credentials
 * @property Collection|UsersContactsInfoCurrent[] $users_contacts_info_currents
 * @property Collection|UsersCredentialsCurrent[] $users_credentials_currents
 * @property Collection|UsersCredentialsHistory[] $users_credentials_histories
 * @property UsersContactsInfo $users_contacts_info
 * @property Collection|UsersContactsInfoHistory[] $users_contacts_info_histories
 *
 * @package App\Models\Base
 */
class UsersCredentialsCurrent extends Model
{
	use SoftDeletes;
	use HasUuid;
	use HasFactory;
	const ID = 'id';
	const USER_ID = 'user_id';
	const USERNAME = 'username';
	const PASSWORD = 'password';
	const REMEMBER_TOKEN = 'remember_token';
	const STAFF_ID = 'staff_id';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	const DELETED_AT = 'deleted_at';
	const USERS_CREDENTIALS_ID = 'users_credentials_id';
	const VERSION = 'version';
	protected $table = 'users_credentials_currents';
	public $incrementing = false;

	protected $casts = [
		self::ID => 'string',
		self::USER_ID => 'uuid',
		self::STAFF_ID => 'uuid',
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime',
		self::DELETED_AT => 'datetime',
		self::USERS_CREDENTIALS_ID => 'uuid',
		self::VERSION => 'int'
	];

	protected $fillable = [
		self::USER_ID,
		self::USERNAME,
		self::PASSWORD,
		self::USERS_CREDENTIALS_ID
	];

	public function users_current(): BelongsTo
	{
		return $this->belongsTo(UsersCurrent::class, UsersCredentialsCurrent::STAFF_ID);
	}

	public function users_credentials_current(): BelongsTo
	{
		return $this->belongsTo(UsersCredentialsCurrent::class, UsersCredentialsCurrent::USERS_CREDENTIALS_ID);
	}

	public function users_credentials(): HasMany
	{
		return $this->hasMany(UsersCredential::class, UsersCredential::USERS_CREDENTIALS_ID);
	}

	public function users_contacts_info_currents(): HasMany
	{
		return $this->hasMany(UsersContactsInfoCurrent::class, UsersContactsInfoCurrent::USER_CREDENTIALS_ID);
	}

	public function users_credentials_currents(): HasMany
	{
		return $this->hasMany(UsersCredentialsCurrent::class, UsersCredentialsCurrent::USERS_CREDENTIALS_ID);
	}

	public function users_credentials_histories(): HasMany
	{
		return $this->hasMany(UsersCredentialsHistory::class, UsersCredentialsHistory::USERS_CREDENTIALS_ID);
	}

	public function users_contacts_info(): HasOne
	{
		return $this->hasOne(UsersContactsInfo::class, UsersContactsInfo::USER_CREDENTIALS_ID);
	}

	public function users_contacts_info_histories(): HasMany
	{
		return $this->hasMany(UsersContactsInfoHistory::class, UsersContactsInfoHistory::USER_CREDENTIALS_ID);
	}
}
