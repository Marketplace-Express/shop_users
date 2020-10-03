<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/04
 * Time: 15:21
 */

namespace App\Http\Controllers\ValidationRules;


class GetRoleRules implements RulesInterface
{

    /**
     * @return \string[][]
     */
    public function getRules(): array
    {
        return [
            'roleId' => ['required', 'uuid']
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