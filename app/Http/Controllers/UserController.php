<?php
/**
 * User: Wajdi Jurry
 * Date: ٢١/٨/٢٠٢٠
 * Time: ٤:٢٠ م
 */

namespace App\Http\Controllers;


use App\Exceptions\DuplicationExist;
use App\Exceptions\NotFound;
use App\Exceptions\OperationFailed;
use App\Exceptions\OperationNotPermitted;
use App\Http\Controllers\ValidationRules\RestoreUserRules;
use App\Http\Requests\BanUserRequest;
use App\Http\Controllers\ValidationRules\BanUserRules;
use App\Http\Controllers\ValidationRules\DeleteUserRules;
use App\Http\Controllers\ValidationRules\LoginUserRules;
use App\Http\Controllers\ValidationRules\RegisterUserRules;
use App\Http\Controllers\ValidationRules\UnBanUserRules;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response as ActionResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseController
{
    private $service;

    /**
     * UserController constructor.
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $this->validation($request, new RegisterUserRules());
            $user = $this->service->create(json_decode($request->getContent(), true));
            $response = $this->prepareResponse($user->toApiArray());
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->validator->errors(), Response::HTTP_BAD_REQUEST);
        } catch (OperationFailed $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_SERVICE_UNAVAILABLE);
        } catch (DuplicationExist $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $this->validation($request, new LoginUserRules());
            $token = $this->service->loginByUsernameOrEmail($request->get('user_name'), $request->get('password'));
            $response = $this->prepareResponse($token->toApiArray());
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (NotFound $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (OperationNotPermitted $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_FORBIDDEN);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param string $userId
     * @return \Illuminate\Http\JsonResponse|ActionResponse
     */
    public function delete(string $userId)
    {
        try {
            $this->validation(new Request(['userId' => $userId]), new DeleteUserRules());
            $this->service->delete($userId);
            return new ActionResponse(null, Response::HTTP_NO_CONTENT);
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (NotFound $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param string $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(string $userId)
    {
        try {
            $this->validation(new Request(['userId' => $userId]), new RestoreUserRules());
            $user = $this->service->restore($userId);
            $response = $this->prepareResponse($user->toApiArray());
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (NotFound $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (DuplicationExist $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param BanUserRequest $request
     * @return \Illuminate\Http\JsonResponse|ActionResponse
     */
    public function ban(BanUserRequest $request)
    {
        try {
            $this->validation($request, new BanUserRules());
            $this->service->ban($request->all());
            $response = new ActionResponse(null, Response::HTTP_NO_CONTENT);
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (NotFound $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (DuplicationExist $exception) {
            $response = $this->prepareResponse('User is already banned');
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param string $userId
     * @return \Illuminate\Http\JsonResponse|ActionResponse
     */
    public function unBan(string $userId)
    {
        try {
            $this->validation(new Request(['userId' => $userId]), new UnBanUserRules());
            $this->service->unBan($userId);
            $response = new ActionResponse(null, Response::HTTP_NO_CONTENT);
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (NotFound $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBanned(Request $request)
    {
        try {
            $bannedUsers = $this->service->getBanned($request->get('page'), $request->get('limit'));
            $response = $this->prepareResponse(
                array_map(function ($user) { return $user->toApiArray(); }, $bannedUsers)
            );
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}