<?php

namespace App\Http\Controllers;


use App\Exceptions\DuplicationExist;
use App\Exceptions\NotFound;
use App\Http\Controllers\Interfaces\Authorizable;
use App\Http\Controllers\ValidationRules\AssignRolePermissionRules;
use App\Http\Controllers\ValidationRules\AssignUnAssignRoleRules;
use App\Http\Controllers\ValidationRules\CreateRoleRules;
use App\Http\Controllers\ValidationRules\DeleteRoleRules;
use App\Http\Controllers\ValidationRules\GetRoleRules;
use App\Http\Controllers\ValidationRules\UnAssignRolePermissionRules;
use App\Http\Controllers\ValidationRules\UpdateRoleRules;
use App\Http\Requests\AssignUnAssignRoleRequest;
use App\Http\Requests\DeleteRolesRequest;
use App\Http\Requests\PermissionRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Policies\Role\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

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
            $this->service->delete($request->route('roleId'));
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
     * @param PermissionRequest $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function assignPermission(PermissionRequest $request)
    {
        try {
            $this->validation($request, new AssignRolePermissionRules($request));
            $this->service->assignPermission($request->route('roleId'), $request->get('permission'));
            $response = new Response(null);
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param PermissionRequest $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function unAssignPermission(PermissionRequest $request)
    {
        try {
            $this->validation($request, new UnAssignRolePermissionRules($request));
            $this->service->unAssignPermission($request->route('roleId'), $request->get('permission'));
            $response = new Response(null, Response::HTTP_NO_CONTENT);
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param UpdateRoleRequest $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function update(UpdateRoleRequest $request)
    {
        try {
            $this->validation($request, new UpdateRoleRules());
            $role = $this->service->update($request->route('roleId'), $request->get('role_name'));
            $response = $this->prepareResponse($role);
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
     * @param AssignUnAssignRoleRequest $request
     * @return \App\Models\UserRole|\Illuminate\Http\JsonResponse
     */
    public function assignRole(AssignUnAssignRoleRequest $request)
    {
        try {
            $this->validation($request, new AssignUnAssignRoleRules());
            $role = $this->service->assignRole($request->route('roleId'), $request->get('user_id'));
            $response = $this->prepareResponse($role);
        } catch (DuplicationExist $exception) {
            $response = $this->prepareResponse('role already assigned to this user', Response::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * @param AssignUnAssignRoleRequest $request
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function unAssignRole(AssignUnAssignRoleRequest $request)
    {
        try {
            $this->validation($request, new AssignUnAssignRoleRules());
            $this->service->unAssignRole($request->route('roleId'), $request->get('user_id'));
            $response = new Response(null, Response::HTTP_NO_CONTENT);
        } catch (NotFound $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
