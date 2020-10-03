<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/21
 * Time: 17:27
 */

namespace App\Http\Controllers\ValidationRules;


interface RulesInterface
{
    public function getRules(): array;

    public function getMessages(): array;
}