<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/11
 * Time: 14:06
 */

namespace App\Policies\User;


use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function deleteUser(User $user): bool
    {
        return false;
    }
}