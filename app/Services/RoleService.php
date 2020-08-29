<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/29
 * Time: 00:12
 */

namespace App\Services;


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

    public function get(string $roleId)
    {

    }

    public function delete(string $roleId)
    {

    }
}