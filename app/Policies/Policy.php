<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/10/17
 * Time: 17:37
 */

namespace App\Policies;


class Policy
{
    static public function FQCN(string $policyModel)
    {
        return __NAMESPACE__ . '\\' . $policyModel . '\\' . $policyModel;
    }

    static public function getPolicyModel(string $policyName, array $data = [])
    {
        $model = app()->make(self::FQCN($policyName));

        foreach ($data as $item => $value) {
            $model->{$item} = $value;
        }

        return $model;
    }
}