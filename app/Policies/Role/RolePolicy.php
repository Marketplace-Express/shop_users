<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/11
 * Time: 12:42
 */

namespace App\Policies\Role;


use App\Models\User;
use App\Repositories\RoleRepository;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

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
     * @param $roleId
     * @return bool
     * @throws \App\Exceptions\NotFound
     */
    public function assignPermissions(User $user, $roleId): bool
    {
        $storeId = $this->repository->getById($roleId)->store_id;
        return $user->isStoreAdmin($storeId) && $user->roles()->hasPermission('assign-permission');
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function viewRole(User $user, Role $role): bool
    {
        try {
            $storeId = $this->repository->getById($role->roleId)->store_id;
            return $user->isStoreAdmin($storeId) && $user->roles()->hasPermission('roles-control');
        } catch (\Throwable $exception) {
            return false;
        }
    }

    /**
     * @param User $user
     * @param Role $role
     * @return bool
     */
    public function createRole(User $user, Role $role): bool
    {
        try {
            return $user->isStoreAdmin($role->storeId);
        } catch (\Throwable $exception) {
            return false;
        }
    }
}