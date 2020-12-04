<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/10/31
 * Time: 01:27
 */

namespace App\Http\Controllers\ValidationRules;


class GetUsersByIdsRules implements RulesInterface
{
    /**
     * @return \string[][]
     */
    public function getRules(): array
    {
        return [
            'usersIds.*' => ['required', 'uuid']
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