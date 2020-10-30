<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/07
 * Time: 23:00
 */

namespace App\Models\Collections;


use Illuminate\Support\Collection;

class RolesCollection extends Collection
{
    /**
     * @param string $permissionKey
     * @return bool
     */
    public function hasPermission(string $permissionKey): bool
    {
        foreach ($this->items as $role) {
            foreach ($role->permissions()->getResults() as $permission) {
                if ($permission->permission_key == $permissionKey) {
                    return true;
                }
            }
        }

        return false;
    }
}