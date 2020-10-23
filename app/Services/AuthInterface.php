<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/26
 * Time: 22:36
 */

namespace App\Services;


use App\Models\Token;

interface AuthInterface
{
    /**
     * @param string $identifier
     * @param string $password
     * @return Token
     */
    public function authenticate(string $identifier, string $password): Token;

    /**
     * @param string $token
     * @param array $permissions
     * @param string $policyModelName
     * @return bool
     */
    public function isAuthorized(string $token, array $permissions, string $policyModelName): bool;
}