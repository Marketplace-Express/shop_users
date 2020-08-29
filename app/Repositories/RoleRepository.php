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
     * @param string $storeId
     * @throws NotFound
     */
    public function delete(string $roleId, string $storeId)
    {
        $role = Role::where('role_id', $roleId)
            ->andWhere('store_id', $storeId)
            ->first();

        if (!$role) {
            throw new NotFound('role already deleted');
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
}