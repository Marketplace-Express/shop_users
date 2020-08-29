<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/28
 * Time: 14:07
 */

namespace App\Enums;


class BanUserReasonsEnum
{
    const USER_BAN_VIOLATES_TERMS = 1;
    const USER_BAN_REQUESTED_BY_USER = 2;
    const USER_BAN_OTHER_REASON = 3;

    public static function getValues(): array
    {
        return [
            self::USER_BAN_VIOLATES_TERMS,
            self::USER_BAN_REQUESTED_BY_USER,
            self::USER_BAN_OTHER_REASON
        ];
    }
}