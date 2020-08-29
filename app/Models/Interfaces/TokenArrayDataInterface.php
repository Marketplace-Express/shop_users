<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/27
 * Time: 11:55
 */

namespace App\Models\Interfaces;


interface TokenArrayDataInterface
{
    /**
     * @return array
     */
    public function toTokenArrayData(): array;
}