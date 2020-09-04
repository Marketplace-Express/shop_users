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
        $response = null; // initialize response variable

        if ($content instanceof ApiArrayData) {
            $response = $content->toApiArray();
        }

        if (is_array($content) || $content instanceof Collection) {
            foreach ($content as $key => $item) {
                $response[$key] = ($item instanceof ApiArrayData) ? $item->toApiArray() : $item;
            }
        }

        return response()->json([
            'status' => $code,
            'message' => $response ?? $content
        ], $code);
    }
}