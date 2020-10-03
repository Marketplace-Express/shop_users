<?php

namespace App\Policies\Store;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->is_super_admin || $user->roles()->hasPermission('create-store');
    }

    /**
     * @param User $user
     * @param Store $store
     * @return bool
     */
    public function update(User $user, Store $store): bool
    {
        return $user->is_super_admin
            || $user->user_id == $store->owner_id
            || $user->roles()->hasPermission('edit-store');
    }
}
