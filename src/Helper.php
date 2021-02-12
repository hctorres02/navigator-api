<?php

namespace HCTorres02\Navigator;

class Helper
{
    public static function canReadFile(string $path)
    {
        return $path && is_file($path) && is_readable($path);
    }

    public static function canReadDir(string $path)
    {
        return $path && is_dir($path) && is_readable($path);
    }
}
