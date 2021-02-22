<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/11/22
 * Time: 00:46
 */

namespace App\Policies\Product;


use App\Models\User;
use App\Policies\AbstractPolicy;

class ProductPolicy extends AbstractPolicy
{
    public function before(User $user, $permission, Product $product)
    {
        if ($user->isSuperAdmin() || $product->storeOwner) {
            return true;
        }

        if (!$this->isStoreAdmin($user, $product->storeId)) {
            return false;
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    public function listProducts(User $user): bool
    {
        return $user->roles()->hasPermission('list-products');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewProduct(User $user): bool
    {
        return $user->roles()->hasPermission('view-product');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function createProduct(User $user): bool
    {
        return $user->roles()->hasPermission('create-product');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function updateProduct(User $user): bool
    {
        return $user->roles()->hasPermission('update-product');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteProduct(User $user): bool
    {
        return $user->roles()->hasPermission('delete-product');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function updateProductQuantity(User $user): bool
    {
        return $user->roles()->hasPermission('update-product-quantity');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function createProductVariation(User $user): bool
    {
        return $user->roles()->hasPermission('create-product-variation');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function updateProductVariation(User $user): bool
    {
        return $user->roles()->hasPermission('update-product-variation');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function deleteProductVariation(User $user): bool
    {
        return $user->roles()->hasPermission('delete-product-variation');
    }
}