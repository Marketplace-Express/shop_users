<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/25
 * Time: 22:33
 */

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

/**
 * Class ConditionalServiceProvider
 * @package App\Providers
 */
class ConditionalServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() : void
    {
        $environment = $this->app->environment();

        $providers = array_unique(array_merge(
            $this->app['config']->get('app.' . 'providers', []),
            $this->app['config']->get('app.' . $environment . '_providers', [])
        ));

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
}