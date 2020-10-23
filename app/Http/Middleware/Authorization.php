<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/09/11
 * Time: 12:21
 */

namespace App\Http\Middleware;


use App\Http\Controllers\Annotations\Permissions;
use App\Http\Controllers\Interfaces\Authorizable;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, \Closure $next)
    {
        $calledAction = $this->parseAction($request->route()[1]['uses']); // temporarily, due to returned array instead of route object
        $permissions = $this->getAnnotation($calledAction);
        $policyModel = $this->getPolicyModelForController($calledAction['controller']);
        if (!empty($permissions)) {
            if ($permissions->operator == Permissions::OPERATOR_AND) {
                if (!app(Gate::class)->forUser(Auth::user())->check($permissions->grants, [$policyModel])) {
                    return response('you are not allowed to do this action', Response::HTTP_UNAUTHORIZED);
                }
            } elseif ($permissions->operator == Permissions::OPERATOR_OR) {
                if (!app(Gate::class)->forUser(Auth::user())->any($permissions->grants, [$policyModel])) {
                    return response('you are not allowed to do this action', Response::HTTP_UNAUTHORIZED);
                }
            }
        }

        return $next($request);
    }

    /**
     * @param string $route
     * @return array
     */
    private function parseAction(string $route): array
    {
        $route = explode('@', $route);
        return ['controller' => $route[0], 'action' => $route[1]];
    }

    /**
     * @param array $calledAction
     * @return object|null
     */
    private function getAnnotation(array $calledAction)
    {
        try {
            AnnotationRegistry::loadAnnotationClass($calledAction['controller']);
            $reflectionClass = new \ReflectionClass($calledAction['controller']);
            $method = $reflectionClass->getMethod($calledAction['action']);
            $reader = new AnnotationReader();
            return $reader->getMethodAnnotation(
                $method,
                Permissions::class
            );
        } catch (\Throwable $exception) {
            return null;
        }
    }

    /**
     * @param string $controller
     * @return string
     * @throws \Exception
     */
    private function getPolicyModelForController(string $controller): string
    {
        $controller = app($controller);

        if (!$controller instanceof Authorizable) {
            throw new \Exception('Controller should implement Authorizable interface');
        }

        return $controller->getPolicyModel();
    }
}
