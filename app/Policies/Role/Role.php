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
 */
class Role
{

    public function __set($attribute, $value)
    {
        $this->{$attribute} = $value;
    }

    public function __get($attribute)
    {
        return $this->{$attribute};
    }
}