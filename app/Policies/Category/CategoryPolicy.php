<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/11/07
 * Time: 15:39
 */

namespace App\Policies\Category;


use App\Models\User;
use App\Policies\AbstractPolicy;

class CategoryPolicy extends AbstractPolicy
{
    public function before(User $user, $permission, Category $category)
    {
        if ($user->isSuperAdmin() || $category->storeOwner) {
            return true;
        }

        if (!$user->isStoreAdmin($category->storeId)) {
            return false;
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    public function createCategory(User $user): bool
    {
        return $user->roles()->hasPermission('create-category');
    }
}