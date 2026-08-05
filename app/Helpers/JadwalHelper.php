<?php

namespace App\Helpers;

class JadwalHelper
{
    protected static $listJam = [
        '06:30 - 07:30', '07:30 - 08:30', '08:30 - 09:30', '09:30 - 10:30',
        '10:30 - 11:30', '11:30 - 12:30', '12:30 - 13:30', '13:30 - 14:30',
        '14:30 - 15:30', '15:30 - 16:30', '16:30 - 17:30',
    ];

    public static function formatJamKul(?string $mvJamkul): string
    {
        if (!$mvJamkul) {
            return 'x (--:--)';
        }

        $groups = explode(' ', trim($mvJamkul));
        $result = [];

        foreach ($groups as $group) {
            if ($group === '') continue;

            $indices = array_map('intval', explode('/', $group));
            sort($indices);

            $firstIdx = $indices[0];
            $lastIdx  = end($indices);

            if (!isset(self::$listJam[$firstIdx]) || !isset(self::$listJam[$lastIdx])) {
                continue;
            }

            [$start] = explode(' - ', self::$listJam[$firstIdx]);
            [, $end] = explode(' - ', self::$listJam[$lastIdx]);

            $jamLabel = implode('/', $indices);
            $result[] = "{$jamLabel} ({$start} - {$end})";
        }

        return $result ? implode(', ', $result) : 'x (--:--)';
    }
}