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
     * @var DataGrabberService
     */
    private $dataGrabber;

    /**
     * RoleService constructor.
     * @param RoleRepository $repository
     * @param DataGrabberService $dataGrabber
     */
    public function __construct(RoleRepository $repository, DataGrabberService $dataGrabber)
    {
        $this->repository = $repository;
        $this->dataGrabber = $dataGrabber;
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
     * @param string $storeId
     * @throws \App\Exceptions\NotFound
     */
    public function delete(string $roleId, string $storeId)
    {
        $this->repository->delete($roleId, $storeId);
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
    public function assign(string $roleId, string $permission)
    {
        $this->repository->assign($roleId, $permission);
    }
}