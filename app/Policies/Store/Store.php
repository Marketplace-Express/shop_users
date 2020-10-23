<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/07
 * Time: 21:18
 */

namespace App\Policies\Store;

/**
 * Class Store
 * @package App\Policies\Store
 *
 * @property $storeId
 * @property $ownerId
 */
class Store
{

    public function __get($attribute)
    {
        return $this->{$attribute};
    }

    public function __set($attribute, $value)
    {
        $this->{$attribute} = $value;
    }
}