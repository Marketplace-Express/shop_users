<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/10/03
 * Time: 21:36
 */

namespace App\Http\Controllers;


use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends BaseController
{
    /**
     * @var AuthService
     */
    private $service;

    /**
     * AuthController constructor.
     * @param AuthService $service
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticated(Request $request)
    {
        try {
            $authData = [
                'is_authenticated' => $this->service->isAuthenticated($request->get('token')),
                'user' => @$this->service->getDecodedToken()->user
            ];
            $response = $this->prepareResponse($authData);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authorized(Request $request)
    {
        try {
            $isAuthorized = $this->service->isAuthorized(
                $request->get('user'),
                $request->get('permissions'),
                $request->get('policyModel'),
                $request->get('operator'),
                $request->get('authorizeData')
            );
            $response = $this->prepareResponse($isAuthorized);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}