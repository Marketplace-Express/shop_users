<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/29
 * Time: 16:19
 */

namespace App\Http\Requests;


use Laravel\Lumen\Http\Request;

class DeleteRolesRequest extends Request
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