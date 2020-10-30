<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/29
 * Time: 00:13
 */

namespace App\Repositories;


use App\Exceptions\DuplicationExist;
use App\Exceptions\NotFound;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\QueryException;
use Ramsey\Uuid\Uuid;

class RoleRepository
{
    /**
     * @param string $roleName
     * @param string $storeId
     * @return Role
     * @throws DuplicationExist
     * @throws \Throwable
     */
    public function create(string $roleName, string $storeId): Role
    {
        $role = new Role([
            'role_id' => Uuid::uuid4()->toString(),
            'role_name' => $roleName,
            'store_id' => $storeId
        ]);

        try {
            $role->saveOrFail();
        } catch (QueryException $exception) {
            if ($exception->getCode() == "23000") {
                throw new DuplicationExist('role already exists');
            }
            throw $exception;
        }

        return $role;
    }

    /**
     * @param string $roleId
     * @return Role
     * @throws NotFound
     */
    public function getById(string $roleId): Role
    {
        $role = Role::firstWhere('role_id', $roleId);

        if (!$role) {
            throw new NotFound('role not found');
        }

        return $role;
    }

    /**
     * @param string $storeId
     * @return array
     */
    public function getByStoreId(string $storeId): array
    {
        $roles = Role::where('store_id', $storeId);
        return $roles->all();
    }

    /**
     * @param string $roleId
     * @throws NotFound
     */
    public function delete(string $roleId)
    {
        $role = Role::firstWhere('role_id', $roleId);

        if (!$role) {
            throw new NotFound('role not found or maybe deleted');
        }

        $role->delete();
    }

    /**
     * @param string $roleId
     * @param string $roleName
     * @return Role
     * @throws DuplicationExist
     * @throws NotFound
     */
    public function update(string $roleId, string $roleName): Role
    {
        $role = Role::firstWhere('role_id', $roleId);

        if (!$role) {
            throw new NotFound('role not found or maybe deleted');
        }

        try {
            $role->role_name = $roleName;
            $role->saveOrFail();
        } catch (QueryException $exception) {
            if ($exception->getCode() == "23000") {
                throw new DuplicationExist('role name already used');
            }
            throw $exception;
        }

        return $role;
    }

    /**
     * @param string $roleId
     * @param string $permission
     * @throws \Throwable
     */
    public function assignPermission(string $roleId, string $permission)
    {
        $permission = new RolePermission([
            'role_id' => $roleId,
            'permission_key' => $permission
        ]);

        $permission->saveOrFail();
    }

    /**
     * @param string $roleId
     * @param string $permission
     * @throws \Throwable
     */
    public function unAssignPermission(string $roleId, string $permission)
    {
        $permission = RolePermission::firstWhere([
            'role_id' => $roleId,
            'permission_key' => $permission
        ]);

        if (!$permission) {
            throw new NotFound('permission is not assigned to role');
        }

        $permission->delete();
    }

    /**
     * @param string $roleId
     * @param string $userId
     * @return Role
     * @throws DuplicationExist
     * @throws NotFound
     */
    public function assignRole(string $roleId, string $userId): Role
    {
        /** @var User $user */
        $user = User::firstWhere('user_id', $userId);

        if (!$user) {
            throw new NotFound('user not found or maybe deleted');
        }

        $role = $this->getById($roleId);
        $user->addRole($role);

        return $role;
    }

    /**
     * @param string $roleId
     * @param string $userId
     * @throws NotFound
     */
    public function unAssignRole(string $roleId, string $userId)
    {
        /** @var User $user */
        $user = User::firstWhere('user_id', $userId);

        if (!$user) {
            throw new NotFound('user not found or maybe deleted');
        }

        $role = $this->getById($roleId);
        $user->removeRole($role);
    }
}