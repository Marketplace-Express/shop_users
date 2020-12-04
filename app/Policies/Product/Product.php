<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/11/22
 * Time: 00:45
 */

namespace App\Policies\Product;

/**
 * Class Product
 * @package App\Policies\Product
 * @property $storeId
 * @property $storeOwner
 */
class Product
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