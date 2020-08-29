<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/29
 * Time: 10:27
 */

namespace App\Models\Traits;


use App\Models\Scopes\BannedUsersScope;

/**
 * Trait BannedUsers
 * @package App\Models\Traits
 */

/**
 * @method static static|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder withBanned()
 * @method static static|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder onlyBanned()
 * @method static static|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder withoutBanned()
 */
trait BannedUsers
{
    /**
     * Boot the banned users trait for a model.
     *
     * @return void
     */
    public static function bootBannedUsers()
    {
        static::addGlobalScope(new BannedUsersScope());
    }

    /**
     * @return string
     */
    public function getIsBannedColumn()
    {
        return defined('static::IS_BANNED') ? static::IS_BANNED : 'is_banned';
    }

    /**
     * @return mixed
     */
    public function getQualifiedIsBannedColumn()
    {
        return $this->qualifyColumn($this->getIsBannedColumn());
    }
}