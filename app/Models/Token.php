<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/26
 * Time: 23:16
 */

namespace App\Models;


use App\Models\Interfaces\ApiArrayData;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Token extends Model implements ApiArrayData, Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    public $incrementing = false;
    public $primaryKey = 'user_id';
    public $keyType = 'string';
    protected $table = 'auth_user_tokens';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'access_token', 'refresh_token', 'csrf_token', 'expires_at'
    ];

    /**
     * @return array
     */
    public function toApiArray(): array
    {
        return [
            'access_token' => $this->access_token,
            'refresh_token' => $this->refresh_token,
            'csrf_token' => $this->csrf_token,
            'expires_at' => Carbon::createFromTimestamp($this->expires_at)->toDateTimeString()
        ];
    }
}