<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/28
 * Time: 14:32
 */

namespace App\Http\Controllers\ValidationRules;


class UnBanUserRules implements RulesInterface
{
    public function getRules(): array
    {
        return [
            'userId' => ['required', 'uuid']
        ];
    }
}