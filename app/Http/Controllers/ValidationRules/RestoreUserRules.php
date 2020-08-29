<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/29
 * Time: 11:09
 */

namespace App\Http\Controllers\ValidationRules;


/**
 * Class RestoreUserRules
 * @package App\Http\Controllers\ValidationRules
 */
class RestoreUserRules implements RulesInterface
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