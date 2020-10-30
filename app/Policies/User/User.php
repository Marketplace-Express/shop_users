<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/10/17
 * Time: 18:03
 */

namespace App\Policies\User;

/**
 * Class User
 * @package App\Policies\User
 *
 * @property $userId
 * @property $storeId
 * @property $storeOwner
 */
class User
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