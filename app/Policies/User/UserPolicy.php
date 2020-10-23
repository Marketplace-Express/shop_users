<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/11
 * Time: 14:06
 */

namespace App\Policies\User;


use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function before(User $user)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function deleteUser(User $user): bool
    {
        return false;
    }

    public function banUser(User $user): bool
    {
        return false;
    }

    public function removeAsAdmin(User $user, $storeId): bool
    {
        return $user->isStoreAdmin($storeId) && $user->roles()->hasPermission("remove-as-admin");
    }
}