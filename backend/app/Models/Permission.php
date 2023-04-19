<?php

namespace App\Models;

use App\Traits\HasUuid;
use Spatie\Permission\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as PermissionSpatie;

class Permission extends PermissionSpatie
{
    use HasFactory, Notifiable, SoftDeletes, HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions_currents';

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
        'name',
        'guard_name',
        'staff_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'guard_name',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function findByPrimary($id)
    {
        return self::where('id', $id)->first();
    }

    /**
     * PermissionController create append guard_name and staff_id
     */
    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);
        if(Auth::check()) $attributes['staff_id'] = Auth::user()->user_id;
        return static::query()->create($attributes);
    }
}
