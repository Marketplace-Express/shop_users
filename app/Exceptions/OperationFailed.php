<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/22
 * Time: 13:18
 */

namespace App\Exceptions;

/**
 * Class OperationFailed
 * @package App\Exceptions
 */
class OperationFailed extends \Exception
{
    public function __construct($message = "", $code = 503, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}