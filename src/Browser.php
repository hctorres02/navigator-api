<?php

namespace HCTorres02\Navigator;

use stdClass;

class Browser
{
    public static function canReadDir(string $path): bool
    {
        return $path && is_readable($path);
    }

    public static function fetch(
        string $path,
        string $filter = NULL,
        array $excluded = ['.']
    ): array {
        $data = new stdClass;
        $data->path = $path;
        $data->filter = $filter;
        $data->excluded = $excluded;

        return self::coordinator($data);
    }

    private static function coordinator(stdClass $data): array
    {
        $result = array();
        $items = array_diff(scandir($data->path), $data->excluded);

        foreach ($items as $item) {
            $entity = self::createEntity($item, $data->path);
            $filterFail = $data->filter && $data->filter != $entity->type;

            if (!$entity || $filterFail) {
                continue;
            }

            $result[$entity->type][] = $entity;
        }

        return $result;
    }

    private static function createEntity(string $name, string $path): ?stdClass
    {
        $realpath = realpath($path . DIRECTORY_SEPARATOR . $name);

        if (!$realpath) {
            return null;
        }

        $entity = new stdClass;
        $entity->name = $name;
        $entity->path = $realpath;
        $entity->type = filetype($realpath);

        return $entity;
    }
}
