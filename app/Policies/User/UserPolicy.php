<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/11
 * Time: 14:06
 */

namespace App\Policies\User;


use App\Models\User;
use App\Policies\AbstractPolicy;
use App\Policies\User\User as UserPolicyModel;
use App\Repositories\UserRepository;

class UserPolicy extends AbstractPolicy
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @param $permission
     * @param \App\Policies\User\User $userPolicyModel
     * @return bool
     */
    public function before(User $user, $permission, UserPolicyModel $userPolicyModel)
    {
        if ($user->isSuperAdmin() || $userPolicyModel->storeOwner) {
            return true;
        }

        if (!$this->isStoreAdmin($user, $userPolicyModel->storeId)) {
            return false;
        }
    }

    public function deleteUser(User $user): bool
    {
        return false;
    }

    public function removeAsAdmin(User $user, $storeId): bool
    {
        return $user->isStoreAdmin($storeId) && $user->roles()->hasPermission('remove-as-admin');
    }
}