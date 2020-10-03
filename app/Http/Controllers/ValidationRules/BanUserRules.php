<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/28
 * Time: 14:04
 */

namespace App\Http\Controllers\ValidationRules;


use App\Enums\BanUserReasonsEnum;
use Illuminate\Validation\Rule;

class BanUserRules implements RulesInterface
{
    /**
     * @return \string[][]
     */
    public function getRules(): array
    {
        return [
            'userId' => ['required', 'uuid'],
            'reason' => ['required', Rule::in(BanUserReasonsEnum::getValues())]
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