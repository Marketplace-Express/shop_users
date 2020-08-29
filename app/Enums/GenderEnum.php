<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/22
 * Time: 12:08
 */

namespace App\Enums;


class GenderEnum
{
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    const GENDER_ALL = 'all';

    public static function getValues(): array
    {
        return [
            self::GENDER_MALE,
            self::GENDER_FEMALE,
            self::GENDER_ALL
        ];
    }

    public static function getMigrationValues(): array
    {
        return [
            self::GENDER_MALE,
            self::GENDER_FEMALE
        ];
    }
}