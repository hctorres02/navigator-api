<?php

namespace HCTorres02\Navigator;

use stdClass;

class Writer
{
    public const ALLOWED_WRITE = Viewer::ALLOWED_VIEWER;

    public static function canWriteFile(string $path): bool
    {
        if (!is_writable($path)) {
            return false;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return in_array($extension, self::ALLOWED_WRITE);
    }

    public static function put(stdClass $entity): void
    {
        file_put_contents($entity->path, $entity->data);
    }
}
