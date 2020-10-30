<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/11
 * Time: 12:43
 */

namespace App\Policies\Role;

/**
 * Class Role
 * @package App\Policies\Role
 *
 * @property $roleId
 * @property $storeId
 * @property $storeOwner
 */
class Role
{
    public function __get($attribute)
    {
        if (property_exists($this, $attribute)) {
            return $this->{$attribute};
        }

        return null;
    }

    public function __set($attribute, $value)
    {
        $this->{$attribute} = $value;
    }
}