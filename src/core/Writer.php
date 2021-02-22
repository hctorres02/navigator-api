<?php

namespace HCTorres02\Navigator\Core;

use HCTorres02\Navigator\Helper;
use HCTorres02\Navigator\Model\Entity;

class Writer
{
    public const ALLOWED_WRITE = [
        'html', 'css', 'txt'
    ];

    public static function canWrite(string $path, bool $skipNative = false): bool
    {
        if ($skipNative || !is_writable($path)) {
            return false;
        }

        $allowed = false;

        foreach (self::ALLOWED_WRITE as $needle) {
            if (Helper::endsWith($path, $needle)) {
                $allowed = true;
                break;
            }
        }

        return $allowed;
    }

    public static function put(Entity $entity): ?int
    {
        return !!file_put_contents($entity->path, $entity->data);
    }

    public static function create(Entity $entity): bool
    {
        return $entity->isDir
            ? mkdir($entity->path, 0777, true)
            : self::put($entity);
    }
}
