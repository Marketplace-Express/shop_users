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
            self::LIST_FOLLOWERS_PERMISSION
        ];
    }
}