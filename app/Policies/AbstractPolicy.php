<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/10/29
 * Time: 13:15
 */

namespace App\Policies;


use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class AbstractPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param string|null $storeId
     * @return bool
     */
    protected function isStoreAdmin(User $user, ?string $storeId = null): bool
    {
        return !empty($storeId) && in_array($storeId, $user->toTokenArrayData()['admin_on_stores']);
    }
}