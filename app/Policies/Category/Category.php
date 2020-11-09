<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/11/07
 * Time: 15:38
 */

namespace App\Policies\Category;

/**
 * Class Category
 * @package App\Policies\Category
 * @property $storeId
 * @property $storeOwner
 */
class Category
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