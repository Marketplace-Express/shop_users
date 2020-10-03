<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/11
 * Time: 12:42
 */

namespace App\Policies\Role;


use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool|null
     */
    public function before(User $user)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    public function assignPermissions(User $user): bool
    {
        return $user->roles()->hasPermission('assign-permission');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function rolesControl(User $user): bool
    {
        return $user->roles()->hasPermission('roles-control');
    }
}