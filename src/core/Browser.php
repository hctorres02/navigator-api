<?php

namespace HCTorres02\Navigator\Core;

use stdClass;
use HCTorres02\Navigator\Model\Entity;

class Browser
{
    public const DENY_PATHS = [];

    public static function canRead(string $path, bool $skipNative = false): bool
    {
        if (!$skipNative && !is_readable($path)) {
            return false;
        }

        $denied = false;

        foreach (self::DENY_PATHS as $denypath) {
            $denypath = realpath($denypath);
            $denypath = str_replace('\\', '/', $denypath);
            $denypath = strtolower($denypath);

            $path = str_replace('\\', '/', $path);
            $path = strtolower($path);

            $len = strlen($denypath);
            $sub = substr($path, 0, $len);

            $denied = $sub == $denypath;

            if ($denied) {
                break;
            }
        }

        return !$denied;
    }

    public static function fetch(string $path, array $excluded = ['.'])
    {
        $data = new stdClass;
        $data->path = $path;
        $data->excluded = $excluded;
        $data->items = [];

        foreach (self::coordinator($data) as $filename) {
            $entity = self::createEntity($filename, $data->path);

            if ($entity) {
                $data->items[] = $entity;
            }
        }

        return $data->items;
    }

    private static function coordinator(stdClass $data)
    {
        $handle = opendir($data->path);

        while (($fileItem = readdir($handle)) != FALSE) {
            if (in_array($fileItem, $data->excluded)) {
                continue;
            }

            yield $fileItem;
        }

        closedir($handle);
    }

    private static function createEntity(string $filename, string $path): ?Entity
    {
        $realpath = realpath($path . DIRECTORY_SEPARATOR . $filename);

        if (!$realpath) {
            return null;
        }

        $entity = new Entity($realpath);
        $entity->name = $filename;

        return $entity;
    }
}
