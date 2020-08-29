<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/21
 * Time: 17:29
 */

namespace App\Http\Controllers\ValidationRules;


use App\Enums\GenderEnum;
use Illuminate\Validation\Rule;

class RegisterUserRules implements RulesInterface
{
    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'first_name' => ['required', 'max:20'],
            'last_name' => ['required', 'max:20'],
            'email' => ['email:filter,spoof'],
            'age' => ['required', 'integer', 'min:18'],
            'gender' => ['required', Rule::in(GenderEnum::getValues())]
        ];
    }
}