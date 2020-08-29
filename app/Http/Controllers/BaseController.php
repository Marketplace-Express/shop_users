<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/21
 * Time: 17:51
 */

namespace App\Http\Controllers;


use App\Http\Controllers\ValidationRules\RulesInterface;
use Illuminate\Http\Request;
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
        $this->validate($request, $rules->getRules());
    }

    /**
     * @param $content
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function prepareResponse($content, int $code = 200)
    {
        return response()->json([
            'status' => $code,
            'message' => $content
        ], $code);
    }
}