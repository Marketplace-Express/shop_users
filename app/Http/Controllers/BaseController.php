<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/21
 * Time: 17:51
 */

namespace App\Http\Controllers;


use App\Http\Controllers\ValidationRules\RulesInterface;
use App\Models\Interfaces\ApiArrayData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Access\Gate;
use Laravel\Lumen\Routing\Controller;

class BaseController extends Controller
{
    /**
     * @param Request $request
     * @param RulesInterface $rules
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validation(Request $request, RulesInterface $rules)
    {
        $this->validate($request, $rules->getRules(), $rules->getMessages());
    }

    /**
     * @param $content
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function prepareResponse($content, int $code = 200)
    {
        $response = null; // initialize response variable

        if (is_array($content) || $content instanceof Collection) {
            array_walk_recursive($content, function (&$value) {
                $value = ($value instanceof ApiArrayData) ? $value->toApiArray() : $value;
            });
        }

        if ($content instanceof ApiArrayData) {
            $response = $content->toApiArray();
        }

        return response()->json([
            'status' => $code,
            'message' => $response ?? $content
        ], $code);
    }

    /**
     * @param $user
     * @param mixed $ability
     * @param array $arguments
     * @return bool|\Illuminate\Auth\Access\Response
     */
    public function authorize($ability, $arguments = [])
    {
        return app(Gate::class)->forUser(Auth::user())->check($ability, $arguments);
    }
}