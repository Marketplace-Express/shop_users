<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/11
 * Time: 12:42
 */

namespace App\Policies\Role;


use App\Models\User;
use App\Policies\AbstractPolicy;
use App\Repositories\RoleRepository;

class RolePolicy extends AbstractPolicy
{
    /**
     * @var RoleRepository
     */
    private $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @param $permission
     * @param Role $role
     * @return bool|null
     */
    public function before(User $user, $permission, Role $role)
    {
        if ($user->isSuperAdmin() || $role->storeOwner) {
            return true;
        }

        if (!$this->isStoreAdmin($user, $role->storeId)) {
            return false;
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
    public function unAssignPermission(User $user): bool
    {
        return $user->roles()->hasPermission('unassign-permission');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewRole(User $user): bool
    {
        return $user->roles()->hasPermission('roles-control');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function createRole(User $user): bool
    {
        return $user->roles()->hasPermission('create-role');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteRole(User $user): bool
    {
        return $user->roles()->hasPermission('delete-role');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function updateRole(User $user): bool
    {
        return $user->roles()->hasPermission('update-role');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function assignRole(User $user): bool
    {
        return $user->roles()->hasPermission('assign-role');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function unAssignRole(User $user): bool
    {
        return $user->roles()->hasPermission('unassign-role');
    }
}