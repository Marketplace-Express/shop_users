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
use App\Http\Controllers\Annotations\Permissions;
use App\Http\Controllers\Interfaces\Authorizable;
use App\Http\Controllers\ValidationRules\GetBannedUsersRules;
use App\Http\Controllers\ValidationRules\GetUsersByIdsRules;
use App\Http\Controllers\ValidationRules\RestoreUserRules;
use App\Http\Requests\BanUserRequest;
use App\Http\Controllers\ValidationRules\BanUserRules;
use App\Http\Controllers\ValidationRules\DeleteUserRules;
use App\Http\Controllers\ValidationRules\LoginUserRules;
use App\Http\Controllers\ValidationRules\RegisterUserRules;
use App\Http\Controllers\ValidationRules\UnBanUserRules;
use App\Policies\Store\Store;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController implements Authorizable
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
     * @return string
     */
    public function getPolicyModel(): string
    {
        return Store::class;
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
            $user = $this->service->loginByUsernameOrEmail($user->email, $request->get('password'));
            $response = $this->prepareResponse($user);
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
            $response = $this->prepareResponse($token);
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
     * @return \Illuminate\Http\JsonResponse|Response
     *
     * @Permissions(grants={"deleteUser"})
     */
    public function delete(string $userId)
    {
        try {
            $this->validation(new Request(['userId' => $userId]), new DeleteUserRules());
            $this->service->delete($userId);
            return new Response(null, Response::HTTP_NO_CONTENT);
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
            $response = $this->prepareResponse($user);
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
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function ban(BanUserRequest $request)
    {
        try {
            $this->validation($request, new BanUserRules());
            $this->service->ban($request->all());
            $response = new Response(null, Response::HTTP_NO_CONTENT);
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (NotFound $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (DuplicationExist $exception) {
            $response = $this->prepareResponse('User is already banned', Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param string $userId
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function unBan(string $userId)
    {
        try {
            $this->validation(new Request(['userId' => $userId]), new UnBanUserRules());
            $this->service->unBan($userId);
            $response = new Response(null, Response::HTTP_NO_CONTENT);
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
            $this->validation($request, new GetBannedUsersRules());
            $bannedUsers = $this->service->getBanned($request->get('page'), $request->get('limit'));
            $response = $this->prepareResponse($bannedUsers);
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByIds(Request $request)
    {
        try {
            $this->validation($request, new GetUsersByIdsRules());
            $response = $this->prepareResponse($this->service->getByIds($request->get('usersIds')));
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}