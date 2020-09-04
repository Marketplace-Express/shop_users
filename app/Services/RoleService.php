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
     * @throws \App\Exceptions\NotFound
     * @throws \App\Exceptions\DuplicationExist
     * @throws \ErrorException
     * @throws \Throwable
     */
    public function create(string $roleName, string $storeId)
    {
        $store = $this->dataGrabber->fetch(
            'stores_sync',
            'store',
            'getById',
            $storeId
        );

        if (empty($store)) {
            throw new NotFound('store not found or maybe deleted');
        }

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

    public function get(string $roleId)
    {

    }
}