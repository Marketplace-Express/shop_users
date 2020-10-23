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
 */
class User
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