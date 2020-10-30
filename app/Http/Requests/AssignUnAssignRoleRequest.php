<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/10/30
 * Time: 16:38
 */

namespace App\Http\Requests;


use Laravel\Lumen\Http\Request;

class AssignUnAssignRoleRequest extends Request
{
    /**
     * @param null $keys
     * @return array
     */
    public function all($keys = null): array
    {
        return array_merge(parent::all($keys), [
            'roleId' => $this->route('roleId')
        ]);
    }
}