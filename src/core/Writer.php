<?php

namespace HCTorres02\Navigator\Core;

use HCTorres02\Navigator\Model\Entity;

class Writer
{
    public const ALLOWED_WRITE = Viewer::ALLOWED_VIEWER;

    public static function canWrite(string $path): bool
    {
        if (!is_writable($path)) {
            return false;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return in_array($extension, self::ALLOWED_WRITE);
    }

    public static function put(Entity $entity): ?int
    {
        return file_put_contents($entity->path, $entity->data);
    }

    public static function create(Entity $entity): bool
    {
        return $entity->isDir
            ? mkdir($entity->path, 0777, true)
            : self::put($entity);
    }
}
