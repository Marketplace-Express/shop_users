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
    const ROLES_CONTROL_PERMISSION = 'roles-control';

    /**
     * @return string[]
     */
    public static function getValues(): array
    {
        return [
            self::EDIT_STORE_PERMISSION,
            self::ASSIGN_PERMISSION_PERMISSION,
            self::ROLES_CONTROL_PERMISSION
        ];
    }
}