<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicationExist;
use App\Exceptions\NotFound;
use App\Http\Controllers\Interfaces\Authorizable;
use App\Http\Controllers\ValidationRules\AssignRolePermissionRules;
use App\Http\Controllers\ValidationRules\CreateRoleRules;
use App\Http\Controllers\ValidationRules\DeleteRoleRules;
use App\Http\Controllers\ValidationRules\GetRoleRules;
use App\Http\Requests\DeleteRolesRequest;
use App\Policies\Role\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Annotations\Permissions;

class RoleController extends BaseController implements Authorizable
{
    /** @var RoleService */
    private $service;

    /**
     * RoleController constructor.
     * @param RoleService $service
     */
    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getPolicyModel(): string
    {
        return Role::class;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @Permissions(operator="or", grants={"createRole", "rolesControl"})
     */
    public function create(Request $request)
    {
        try {
            $this->validation($request, new CreateRoleRules());
            $role = $this->service->create($request->get('role_name'), $request->get('store_id'));
            $response = $this->prepareResponse($role);
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (DuplicationExist $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param DeleteRolesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteRolesRequest $request)
    {
        try {
            $this->validation($request, new DeleteRoleRules());
            $this->service->delete($request->route('roleId'), $request->get('storeId'));
            $response = new Response(null);
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
     * @param string $roleId
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(string $roleId)
    {
        try {
            $this->validation(new Request(['roleId' => $roleId]), new GetRoleRules());
            $response = $this->prepareResponse($this->service->get($roleId));
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
     * @return \Illuminate\Http\JsonResponse|Response
     *
     * @Permissions(operator="or", grants={"assignPermissions", "rolesControl"})
     */
    public function assign(Request $request)
    {
        try {
            $this->validation($request, new AssignRolePermissionRules($request));
            $this->service->assign($request->get('role_id'), $request->get('permission'));
            $response = new Response(null);
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
