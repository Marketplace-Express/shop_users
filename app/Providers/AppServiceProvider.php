<?php

namespace App\Providers;

use App\Http\Requests\BanUserRequest;
use App\Http\Requests\DeleteRolesRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if(env('APP_DEBUG')) {
            DB::listen(function($query) {
                File::append(
                    storage_path('/logs/query.log'),
                    $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL
                );
            });
        }

        // Custom request objects for different controller actions
        $this->app->resolving(BanUserRequest::class, function ($request, $app) {
            BanUserRequest::createFrom($app['request'], $request);
        });
        $this->app->resolving(DeleteRolesRequest::class, function ($request, $app) {
            DeleteRolesRequest::createFrom($app['request'], $request);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\Illuminate\Contracts\Routing\ResponseFactory::class, function() {
            return new \Laravel\Lumen\Http\ResponseFactory();
        });

        $this->app->singleton(\Jurry\RabbitMQ\Handler\AmqpHandler::class, function () {
            return new \Jurry\RabbitMQ\Handler\AmqpHandler(
                env('JURRY_RABBITMQ_HOST'),
                env('JURRY_RABBITMQ_PORT'),
                env('JURRY_RABBITMQ_USERNAME'),
                env('JURRY_RABBITMQ_PASSWORD'),
                [
                    'sync_queue' => [
                        'name' => 'users_sync',
                        'message_ttl' => 10000
                    ],
                    'async_queue' => [
                        'name' => 'users_async',
                        'message_ttl' => 10000
                    ],
                ]
            );
        });
    }
}
