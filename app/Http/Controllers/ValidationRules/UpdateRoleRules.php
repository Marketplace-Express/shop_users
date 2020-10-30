<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/10/28
 * Time: 15:27
 */

namespace App\Http\Controllers\ValidationRules;


class UpdateRoleRules implements RulesInterface
{

    /**
     * @return \string[][]
     */
    public function getRules(): array
    {
        return [
            'roleId' => ['required', 'uuid'],
            'role_name' => ['required', 'string', 'min:3', 'max:50']
        ];
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return [];
    }
}