<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/22
 * Time: 13:52
 */

namespace App\Models\Interfaces;


interface ApiArrayData
{
    public function toApiArray(): array;
}