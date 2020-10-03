<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use App\Services\AuthService;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * @var AuthService
     */
    private $authService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     * @param AuthService $authService
     * @param UserRepository $userRepository
     */
    public function __construct(Auth $auth, AuthService $authService, UserRepository $userRepository)
    {
        $this->auth = $auth;
        $this->authService = $authService;
        $this->userRepository = $userRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->bearerToken() || !$this->authService->isAuthenticated($request->bearerToken())) {
            return response('Unauthorized', 401);
        }

        // Authorize User
        \Illuminate\Support\Facades\Auth::setUser(
            $this->userRepository->getById($this->authService->getDecodedToken()->user->user_id)
        );

        return $next($request);
    }
}
