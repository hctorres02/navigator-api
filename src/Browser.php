<?php

namespace HCTorres02\Navigator;

use stdClass;

class Browser
{
    public const DENY_PATHS = [
        //'c:/windows'
    ];

    public static function canRead(string $path): bool
    {
        if (!is_readable($path)) {
            return false;
        }

        $denied = false;

        foreach (self::DENY_PATHS as $denypath) {
            $realdenypath = realpath($denypath);
            $len = strlen($realdenypath);
            $sub = substr($path, 0, $len);
            $denied = $sub == $realdenypath;

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

        $entity = new Entity($realpath, NULL, TRUE);
        $entity->name = $filename;
        unset($entity->data);

        return $entity;
    }
}
