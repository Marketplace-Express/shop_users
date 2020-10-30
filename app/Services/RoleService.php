<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/29
 * Time: 00:12
 */

namespace App\Services;


use App\Exceptions\NotFound;
use App\Repositories\RoleRepository;

/**
 * Class RoleService
 * @package App\Services
 */
class RoleService
{
    /** @var RoleRepository */
    private $repository;

    /**
     * RoleService constructor.
     * @param RoleRepository $repository
     */
    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $roleName
     * @param string $storeId
     * @return \App\Models\Role
     * @throws \App\Exceptions\DuplicationExist
     * @throws \Throwable
     */
    public function create(string $roleName, string $storeId)
    {
        return $this->repository->create($roleName, $storeId);
    }

    /**
     * @param string $roleId
     * @throws \App\Exceptions\NotFound
     */
    public function delete(string $roleId)
    {
        $this->repository->delete($roleId);
    }

    /**
     * @param string $roleId
     * @return \App\Models\Role
     * @throws NotFound
     */
    public function get(string $roleId)
    {
        return $this->repository->getById($roleId);
    }

    /**
     * @param string $roleId
     * @param string $permission
     * @throws \Throwable
     */
    public function assignPermission(string $roleId, string $permission)
    {
        $this->repository->assignPermission($roleId, $permission);
    }

    /**
     * @param string $roleId
     * @param string $permission
     * @throws \Throwable
     */
    public function unAssignPermission(string $roleId, string $permission)
    {
        $this->repository->unAssignPermission($roleId, $permission);
    }

    /**
     * @param string $roleId
     * @param string $roleName
     * @return \App\Models\Role
     * @throws NotFound
     * @throws \App\Exceptions\DuplicationExist
     */
    public function update(string $roleId, string $roleName)
    {
        return $this->repository->update($roleId, $roleName);
    }

    /**
     * @param string $roleId
     * @param string $userId
     * @return \App\Models\Role
     * @throws NotFound
     * @throws \App\Exceptions\DuplicationExist
     */
    public function assignRole(string $roleId, string $userId)
    {
        return $this->repository->assignRole($roleId, $userId);
    }

    /**
     * @param string $roleId
     * @param string $userId
     * @throws NotFound
     */
    public function unAssignRole(string $roleId, string $userId)
    {
        $this->repository->unAssignRole($roleId, $userId);
    }
}