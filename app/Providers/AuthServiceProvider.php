<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/04
 * Time: 19:35
 */

namespace App\Providers;


use Illuminate\Auth\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $policies = [
        'App\Policies\Store\Store' => 'App\Policies\Store\StorePolicy',
        'App\Policies\Role\Role' => 'App\Policies\Role\RolePolicy',
        'App\Policies\User\User' => 'App\Policies\User\UserPolicy',
    ];

    public function boot()
    {
        foreach ($this->policies as $class => $policy) {
            Gate::policy($class, $policy);
        }
    }
}