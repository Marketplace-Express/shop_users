<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/10/24
 * Time: 14:50
 */

namespace App\Exceptions;


class RoleUsed extends \Exception
{
    public function __construct($message = "this role is already used", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}