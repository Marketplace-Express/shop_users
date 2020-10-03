<?php

namespace App\Models;

use App\Models\Collections\RolesCollection;
use App\Models\Interfaces\ApiArrayData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

/**
 * Class Role
 * @package App\Models
 */
class Role extends Model implements ApiArrayData
{
    use SoftDeletes;

    public $incrementing = false;
    public $primaryKey = 'role_id';
    public $keyType = 'string';

    public $fillable = [
        'role_id', 'role_name', 'store_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->deleted_at = new \DateTime();
            $model->deletion_token = Uuid::uuid4()->toString();
            $model->save();
            return false;
        });
    }

    public function user()
    {
        return $this->belongsToMany(Role::class)->using(UserRole::class)->withPivot([
            'user_id', 'role_id'
        ]);
    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id', 'role_id')->getResults();
    }

    /**
     * @param array $models
     * @return RolesCollection|\Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new RolesCollection($models);
    }

    /**
     * @return array
     */
    public function toApiArray(): array
    {
        return [
            'role_id' => $this->role_id,
            'role_name' => $this->role_name,
            'store_id' => $this->store_id
        ];
    }
}
