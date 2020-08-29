<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/24
 * Time: 00:21
 */

namespace App\Exceptions;

/**
 * Class DuplicationExist
 * @package App\Exceptions
 */
class DuplicationExist extends \Exception
{
    public function __construct($message = "", $code = 400, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}