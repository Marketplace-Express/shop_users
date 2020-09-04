<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/29
 * Time: 16:09
 */

namespace App\Http\Controllers\ValidationRules;


/**
 * Class DeleteRoleRules
 * @package App\Http\Controllers\ValidationRules
 */
class DeleteRoleRules implements RulesInterface
{

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'roleId' => ['required', 'uuid'],
            'storeId' => ['required', 'uuid']
        ];
    }
}