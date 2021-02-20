<?php

namespace HCTorres02\Navigator;

define('GET', 'GET');
define('POST', 'POST');
define('PUT', 'PUT');
define('DELETE', 'DELETE');

class Helper
{
    public const ALLOWED_METHODS = [
        GET, POST, PUT, DELETE
    ];

    public const ALLOWED_CREATE_TYPES = [
        self::FILE, self::FOLDER
    ];

    private const FOLDER = 'folder';
    private const FILE = 'file';

    public static function pathWrapper(string $drive, string $path = ''): string
    {
        $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $wrapped = $isWin ? "{$drive}:{$path}" : "/{$drive}{$path}";

        return $wrapped;
    }

    public static function typeIsFolder(string $type)
    {
        return $type === self::FOLDER;
    }
}
