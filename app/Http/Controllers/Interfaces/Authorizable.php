<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/11
 * Time: 13:23
 */

namespace App\Http\Controllers\Interfaces;


interface Authorizable
{
    /**
     * @return string The policy class name
     */
    public function getPolicyModel(): string;
}