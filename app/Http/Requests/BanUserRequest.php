<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/28
 * Time: 15:02
 */

namespace App\Http\Requests;


use Laravel\Lumen\Http\Request;

class BanUserRequest extends Request
{
    /**
     * @param null $keys
     * @return array
     */
    public function all($keys = null): array
    {
        return array_merge(parent::all($keys), [
            'userId' => $this->route('userId')
        ]);
    }
}