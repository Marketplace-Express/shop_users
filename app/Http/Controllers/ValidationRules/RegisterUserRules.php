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
            'email' => ['required', 'email:filter,spoof'],
            'gender' => ['required', Rule::in(GenderEnum::getValues())],
            'birthdate' => ['required', 'date_format:Y-m-d'],
            'password' => ['required', 'min:6'],
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