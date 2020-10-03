<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/09
 * Time: 21:47
 */

namespace App\Http\Controllers\ValidationRules;


use App\Enums\PermissionsEnum;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssignRolePermissionRules implements RulesInterface
{

    /**
     * @var Request
     */
    private $request;

    /**
     * AssignRolePermissionRules constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'role_id' => ['required', 'uuid'],
            'permission' => [
                'required',
                Rule::in(PermissionsEnum::getValues()),
                Rule::unique('role_permissions', 'permission_key')->where(function ($query) {
                    return $query->where('role_id', $this->request->get('role_id'));
                })
            ]
        ];
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return [
            'permission.unique' => 'The permission has already been assigned'
        ];
    }
}