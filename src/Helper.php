<?php

namespace HCTorres02\Navigator;

class Helper
{
    public const ALLOWED_METHOD = ['GET', 'POST', 'PUT', 'DELETE'];

    public static function pathWrapper(
        string $drive,
        string $path = NULL,
        bool $ignorePath = FALSE
    ): string {
        if ($ignorePath) {
            return $drive;
        }

        $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $wrapped = $isWin ? "{$drive}:{$path}" : "/{$drive}{$path}";

        return $wrapped;
    }
}
