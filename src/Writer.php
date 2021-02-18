<?php

namespace HCTorres02\Navigator;

class Writer
{
    public static function canWriteFile(string $path): bool
    {
        return $path && is_writable($path);
    }
}
