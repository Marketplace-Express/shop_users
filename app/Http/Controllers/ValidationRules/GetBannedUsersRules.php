<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/29
 * Time: 12:11
 */

namespace App\Http\Controllers\ValidationRules;

/**
 * Class GetBannedUsersRules
 * @package App\Http\Controllers\ValidationRules
 */
class GetBannedUsersRules implements RulesInterface
{

    /**
     * @return \string[][]
     */
    public function getRules(): array
    {
        return [
            'page' => ['required', 'integer', 'min:1'],
            'limit' => ['required', 'integer', 'min:1']
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