<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/26
 * Time: 22:36
 */

namespace App\Services;


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
     * @param User $user
     * @param $permission
     * @return bool
     */
    public function isAuthorized(User $user, $permission): bool;
}