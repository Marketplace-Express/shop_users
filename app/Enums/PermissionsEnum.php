<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/09
 * Time: 21:48
 */

namespace App\Enums;


class PermissionsEnum
{
    const EDIT_STORE_PERMISSION = 'edit-store';
    const ASSIGN_PERMISSION_PERMISSION = 'assign-permission';
    const UNASSIGN_PERMISSION = 'unassign-permission';
    const ROLES_CONTROL_PERMISSION = 'roles-control';
    const BAN_USER_PERMISSION = 'ban-user';
    const CREATE_ROLE_PERMISSION = 'create-role';
    const DELETE_ROLE_PERMISSION = 'delete-role';
    const UPDATE_ROLE_PERMISSION = 'update-role';
    const ASSIGN_ROLE_PERMISSION = 'assign-role';
    const UNASSIGN_ROLE_PERMISSION = 'unassign-role';
    const LIST_FOLLOWERS_PERMISSION = 'list-followers';
    const CREATE_CATEGORY_PERMISSION = 'create-category';
    const UPDATE_CATEGORY_PERMISSION = 'update-category';
    const DELETE_CATEGORY_PERMISSION = 'delete-category';
    const LIST_PRODUCTS_PERMISSION = 'list-products';
    const VIEW_PRODUCT_PERMISSION = 'view-product';
    const CREATE_PRODUCT_PERMISSION = 'create-product';
    const UPDATE_PRODUCT_PERMISSION = 'update-product';
    const DELETE_PRODUCT_PERMISSION = 'delete-product';
    const UPDATE_PRODUCT_QUANTITY_PERMISSION = 'update-product-quantity';
    const CREATE_PRODUCT_VARIATION_PERMISSION = 'create-product-variation';
    const UPDATE_PRODUCT_VARIATION_PERMISSION = 'update-product-variation';
    const DELETE_PRODUCT_VARIATION_PERMISSION = 'delete-product-variation';

    /**
     * @return string[]
     */
    public static function getValues(): array
    {
        return [
            self::EDIT_STORE_PERMISSION,
            self::ASSIGN_PERMISSION_PERMISSION,
            self::UNASSIGN_PERMISSION,
            self::ROLES_CONTROL_PERMISSION,
            self::BAN_USER_PERMISSION,
            self::CREATE_ROLE_PERMISSION,
            self::DELETE_ROLE_PERMISSION,
            self::UPDATE_ROLE_PERMISSION,
            self::ASSIGN_ROLE_PERMISSION,
            self::UNASSIGN_ROLE_PERMISSION,
            self::LIST_FOLLOWERS_PERMISSION,
            self::CREATE_CATEGORY_PERMISSION,
            self::UPDATE_CATEGORY_PERMISSION,
            self::DELETE_CATEGORY_PERMISSION,
            self::LIST_PRODUCTS_PERMISSION,
            self::VIEW_PRODUCT_PERMISSION,
            self::CREATE_PRODUCT_PERMISSION,
            self::UPDATE_PRODUCT_PERMISSION,
            self::DELETE_PRODUCT_PERMISSION,
            self::UPDATE_PRODUCT_QUANTITY_PERMISSION,
            self::CREATE_PRODUCT_VARIATION_PERMISSION,
            self::UPDATE_PRODUCT_VARIATION_PERMISSION,
            self::DELETE_PRODUCT_VARIATION_PERMISSION,
        ];
    }
}