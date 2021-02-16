<?php

namespace HCTorres02\Navigator;

class Helper
{
    public const ALLOWED_VIEWER = [
        'css', 'csv', 'htm', 'html', 'js',
        'json', 'php', 'sql', 'txt', 'xml'
    ];

    public const ALLOWED_DOWNLOAD = [];

    public static function canReadFile(string $path): bool
    {
        return $path && is_file($path) && is_readable($path);
    }

    public static function canReadDir(string $path): bool
    {
        return $path && is_dir($path) && is_readable($path);
    }

    public static function canViewFile(string $path): bool
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return in_array($extension, self::ALLOWED_VIEWER);
    }

    public static function canDownloadFile(string $path): bool
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return in_array($extension, self::ALLOWED_DOWNLOAD);
    }

    public static function pathWrapper(string $drive, string $path): string
    {
        $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $wrapped = $isWin ? "{$drive}:{$path}" : "/{$drive}{$path}";

        return $wrapped;
    }
}
