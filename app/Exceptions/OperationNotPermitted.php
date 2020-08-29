<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/22
 * Time: 17:26
 */

namespace App\Exceptions;


/**
 * Class OperationNotPermitted
 * @package App\Exceptions
 */
class OperationNotPermitted extends \Exception
{
    public function __construct($message = "", $code = 403, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}