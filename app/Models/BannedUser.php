<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/28
 * Time: 14:17
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class BannedUser
 * @package App\Models
 */
class BannedUser extends Model
{
    public $primaryKey = 'user_id';

    public $incrementing = false;

    public $keyType = 'string';

    public $timestamps = false;

    public $table = 'banned_users';

    public $fillable = [
        'user_id', 'reason', 'description'
    ];
}