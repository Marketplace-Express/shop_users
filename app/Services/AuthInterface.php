<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/26
 * Time: 22:36
 */

namespace App\Services;


use App\Http\Controllers\Annotations\Permissions;
use App\Models\Token;
use App\Models\User;

interface AuthInterface
{
    /**
     * @param string $identifier
     * @param string $password
     * @return Token
     */
    public function authenticate(string $identifier, string $password): Token;

    /**
     * @param string $userId
     * @param array $permissionsAsked
     * @return bool
     */
    public function isAuthorized(string $userId, array $permissionsAsked): bool;
}