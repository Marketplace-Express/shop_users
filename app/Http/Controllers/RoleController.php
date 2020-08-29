<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicationExist;
use App\Http\Controllers\ValidationRules\CreateRoleRules;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class RoleController extends BaseController
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $this->validation($request, new CreateRoleRules());
            $role = $this->service->create($request->get('role_name'), $request->get('store_id'));
            $response = $this->prepareResponse($role->toApiArray());
        } catch (ValidationException $exception) {
            $response = $this->prepareResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        } catch (DuplicationExist $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $response = $this->prepareResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
