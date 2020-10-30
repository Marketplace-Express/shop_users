<?php

namespace App\Policies\Store;

use App\Models\User;
use App\Policies\AbstractPolicy;

class StorePolicy extends AbstractPolicy
{
    /**
     * @param User $user
     * @param $permission
     * @param Store $store
     * @return bool
     */
    public function before(User $user, $permission, Store $store)
    {
        if ($user->isSuperAdmin() || $store->storeOwner) {
            return true;
        }

        if (!$this->isStoreAdmin($user, $store->storeId)) {
            return false;
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    public function updateStore(User $user): bool
    {
        return $user->roles()->hasPermission('edit-store');
    }

    /**
     * @param User $user
     * @param Store $store
     * @return bool
     */
    public function deleteStore(User $user, Store $store): bool
    {
        return (bool) $store->storeOwner;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function listFollowers(User $user): bool
    {
        return $user->roles()->hasPermission('list-followers');
    }
}
