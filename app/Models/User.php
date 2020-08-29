<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/23
 * Time: 22:33
 */

namespace App\Models;


use App\Exceptions\DuplicationExist;
use App\Models\Interfaces\ApiArrayData;
use App\Models\Interfaces\TokenArrayDataInterface;
use App\Models\Traits\BannedUsers;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Laravel\Lumen\Auth\Authorizable;
use Ramsey\Uuid\Uuid;

/**
 * Class User
 * @package App\Models
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, ApiArrayData, TokenArrayDataInterface
{
    use Authenticatable, Authorizable, SoftDeletes, BannedUsers;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    public $primaryKey = 'user_id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    public $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_banned' => 'boolean'
    ];

    /**
     * Overrides deletion process by setting custom attributes
     */
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->deletion_token = Uuid::uuid4()->toString();
            $model->deleted_at = new \DateTime();
            $model->save();
            return false;
        });
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->is_banned;
    }

    /**
     * @return array
     */
    public function toApiArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'user_name' => $this->user_name,
            'age' => $this->age,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate
        ];
    }

    /**
     * @return array
     */
    public function toTokenArrayData(): array
    {
        return [
            'user_id' => $this->getAuthIdentifier(),
            'email' => $this->email,
            'user_name' => $this->user_name
        ];
    }
}
