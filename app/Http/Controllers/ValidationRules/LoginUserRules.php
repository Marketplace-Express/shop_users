<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/22
 * Time: 16:09
 */

namespace App\Http\Controllers\ValidationRules;


class LoginUserRules implements RulesInterface
{

    /**
     * @return \string[][]
     */
    public function getRules(): array
    {
        return [
            'user_name' => ['required'],
            'password' => ['required']
        ];
    }
}