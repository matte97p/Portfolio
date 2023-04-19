<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\ModelHasPermission;
use App\Models\ModelHasRole;
use App\Models\Permission;
use App\Models\PermissionsCurrent;
use App\Models\PermissionsHistory;
use App\Models\Role;
use App\Models\RoleHasPermission;
use App\Models\RolesCurrent;
use App\Models\RolesHistory;
use App\Models\User;
use App\Models\UsersContactsInfo;
use App\Models\UsersContactsInfoCurrent;
use App\Models\UsersContactsInfoHistory;
use App\Models\UsersCredential;
use App\Models\UsersCredentialsCurrent;
use App\Models\UsersCredentialsHistory;
use App\Models\UsersHistory;
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
 * Class UsersCurrent
 * 
 * @property uuid $id
 * @property string $name
 * @property string $surname
 * @property string $taxid
 * @property string $gender
 * @property Carbon $birth_date
 * @property uuid|null $staff_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property uuid|null $users_id
 * @property int $version
 * 
 * @property UsersCurrent|null $users_current
 * @property Collection|UsersCurrent[] $users_currents
 * @property Collection|PermissionsCurrent[] $permissions_currents
 * @property Collection|UsersHistory[] $users_histories
 * @property Collection|User[] $users
 * @property Collection|ModelHasRole[] $model_has_roles
 * @property Collection|Role[] $roles
 * @property Collection|RolesCurrent[] $roles_currents
 * @property Collection|PermissionsHistory[] $permissions_histories
 * @property Collection|RolesHistory[] $roles_histories
 * @property Collection|Permission[] $permissions
 * @property Collection|ModelHasPermission[] $model_has_permissions
 * @property Collection|RoleHasPermission[] $role_has_permissions
 * @property Collection|UsersCredential[] $users_credentials
 * @property Collection|UsersCredentialsCurrent[] $users_credentials_currents
 * @property Collection|UsersCredentialsHistory[] $users_credentials_histories
 * @property UsersContactsInfo $users_contacts_info
 * @property Collection|UsersContactsInfoCurrent[] $users_contacts_info_currents
 * @property Collection|UsersContactsInfoHistory[] $users_contacts_info_histories
 *
 * @package App\Models\Base
 */
class UsersCurrent extends Model
{
	use SoftDeletes;
	use HasUuid;
	use HasFactory;
	const ID = 'id';
	const NAME = 'name';
	const SURNAME = 'surname';
	const TAXID = 'taxid';
	const GENDER = 'gender';
	const BIRTH_DATE = 'birth_date';
	const STAFF_ID = 'staff_id';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	const DELETED_AT = 'deleted_at';
	const USERS_ID = 'users_id';
	const VERSION = 'version';
	protected $table = 'users_currents';
	public $incrementing = false;

	protected $casts = [
		self::ID => 'uuid',
		self::BIRTH_DATE => 'datetime',
		self::STAFF_ID => 'uuid',
		self::CREATED_AT => 'datetime',
		self::UPDATED_AT => 'datetime',
		self::DELETED_AT => 'datetime',
		self::USERS_ID => 'uuid',
		self::VERSION => 'int'
	];

	protected $fillable = [
		self::NAME,
		self::SURNAME,
		self::TAXID,
		self::GENDER,
		self::BIRTH_DATE,
		self::USERS_ID
	];

	public function users_current(): BelongsTo
	{
		return $this->belongsTo(UsersCurrent::class, UsersCurrent::USERS_ID);
	}

	public function users_currents(): HasMany
	{
		return $this->hasMany(UsersCurrent::class, UsersCurrent::USERS_ID);
	}

	public function permissions_currents(): HasMany
	{
		return $this->hasMany(PermissionsCurrent::class, PermissionsCurrent::STAFF_ID);
	}

	public function users_histories(): HasMany
	{
		return $this->hasMany(UsersHistory::class, UsersHistory::USERS_ID);
	}

	public function users(): HasMany
	{
		return $this->hasMany(User::class, User::USERS_ID);
	}

	public function model_has_roles(): HasMany
	{
		return $this->hasMany(ModelHasRole::class, ModelHasRole::STAFF_ID);
	}

	public function roles(): HasMany
	{
		return $this->hasMany(Role::class, Role::STAFF_ID);
	}

	public function roles_currents(): HasMany
	{
		return $this->hasMany(RolesCurrent::class, RolesCurrent::STAFF_ID);
	}

	public function permissions_histories(): HasMany
	{
		return $this->hasMany(PermissionsHistory::class, PermissionsHistory::STAFF_ID);
	}

	public function roles_histories(): HasMany
	{
		return $this->hasMany(RolesHistory::class, RolesHistory::STAFF_ID);
	}

	public function permissions(): HasMany
	{
		return $this->hasMany(Permission::class, Permission::STAFF_ID);
	}

	public function model_has_permissions(): HasMany
	{
		return $this->hasMany(ModelHasPermission::class, ModelHasPermission::STAFF_ID);
	}

	public function role_has_permissions(): HasMany
	{
		return $this->hasMany(RoleHasPermission::class, RoleHasPermission::STAFF_ID);
	}

	public function users_credentials(): HasMany
	{
		return $this->hasMany(UsersCredential::class, UsersCredential::STAFF_ID);
	}

	public function users_credentials_currents(): HasMany
	{
		return $this->hasMany(UsersCredentialsCurrent::class, UsersCredentialsCurrent::STAFF_ID);
	}

	public function users_credentials_histories(): HasMany
	{
		return $this->hasMany(UsersCredentialsHistory::class, UsersCredentialsHistory::STAFF_ID);
	}

	public function users_contacts_info(): HasOne
	{
		return $this->hasOne(UsersContactsInfo::class, UsersContactsInfo::STAFF_ID);
	}

	public function users_contacts_info_currents(): HasMany
	{
		return $this->hasMany(UsersContactsInfoCurrent::class, UsersContactsInfoCurrent::STAFF_ID);
	}

	public function users_contacts_info_histories(): HasMany
	{
		return $this->hasMany(UsersContactsInfoHistory::class, UsersContactsInfoHistory::STAFF_ID);
	}
}
