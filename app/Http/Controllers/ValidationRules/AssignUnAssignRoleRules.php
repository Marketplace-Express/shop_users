<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/10/30
 * Time: 16:46
 */

namespace App\Http\Controllers\ValidationRules;


class AssignUnAssignRoleRules implements RulesInterface
{
    /**
     * @return \string[][]
     */
    public function getRules(): array
    {
        return [
            'roleId' => ['required', 'uuid'],
            'user_id' => ['required', 'uuid']
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