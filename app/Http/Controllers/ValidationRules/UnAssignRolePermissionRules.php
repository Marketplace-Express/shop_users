<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/10/28
 * Time: 23:39
 */

namespace App\Http\Controllers\ValidationRules;


use App\Enums\PermissionsEnum;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnAssignRolePermissionRules implements RulesInterface
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
            'roleId' => ['required', 'uuid'],
            'permission' => [
                'required',
                Rule::in(PermissionsEnum::getValues()),
                Rule::exists('role_permissions', 'permission_key')->where(function ($query) {
                    return $query->where('role_id', $this->request->route('roleId'));
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
            'permission.exists' => 'permission is not assigned to role'
        ];
    }
}