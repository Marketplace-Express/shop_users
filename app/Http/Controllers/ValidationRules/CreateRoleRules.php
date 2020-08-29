<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/29
 * Time: 10:15
 */

namespace App\Http\Controllers\ValidationRules;


class CreateRoleRules implements RulesInterface
{
    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'role_name' => ['required'],
            'store_id' => ['required', 'uuid']
        ];
    }
}