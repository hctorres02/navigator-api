<?php

namespace HCTorres02\Navigator\Core;

class Viewer
{
    public const ALLOWED_VIEWER = [
        'css', 'csv', 'htm', 'html', 'js',
        'json', 'php', 'sql', 'txt', 'xml'
    ];

    public static function canView(string $path): bool
    {
        if (!is_readable($path)) {
            return false;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return in_array($extension, self::ALLOWED_VIEWER);
    }

    public static function get(string $path): ?string
    {
        $data = null;

        ob_start();
        $data = file_get_contents($path);
        ob_flush();

        return $data;
    }
}
