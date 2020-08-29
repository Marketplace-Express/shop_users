<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/23
 * Time: 23:42
 */

namespace App\Http\Controllers\ValidationRules;


class DeleteUserRules implements RulesInterface
{

    /**
     * @return \string[][]
     */
    public function getRules(): array
    {
        return [
            'userId' => ['required', 'uuid']
        ];
    }
}