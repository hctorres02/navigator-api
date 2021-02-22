<?php

namespace HCTorres02\Navigator\Core;

use HCTorres02\Navigator\Helper;

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

        $allowed = false;

        foreach (self::ALLOWED_VIEWER as $needle) {
            if (Helper::endsWith($path, $needle)) {
                $allowed = true;
                break;
            }
        }

        return $allowed;
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
