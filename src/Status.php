<?php

namespace App;

class Status
{
    public const TO_DO = 1;
    public const DOING = 2;
    public const DONE = 3;
    public const STATUSES = [
        self::TO_DO => 'To Do',
        self::DOING => 'Doing',
        self::DONE => 'Done',
    ];

    public static function getRandom(): int
    {
        return array_rand(self::STATUSES);
    }
}
