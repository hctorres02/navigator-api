<?php

namespace HCTorres02\Navigator;

use stdClass;

class Browser
{
    public static function fetch(
        string $path,
        string $filter = NULL,
        array $excluded = ['.']
    ): string {
        $data = new stdClass;
        $data->path = realpath($path);
        $data->filter = $filter;
        $data->excluded = $excluded;

        if (Helper::canReadDir($data->path)) {
            $data->entities = self::coordinator($data);
        }

        return json_encode($data);
    }

    private static function coordinator(stdClass $data): array
    {
        $result = array();
        $items = array_diff(scandir($data->path), $data->excluded);

        foreach ($items as $item) {
            $entity = self::createEntity($item, $data->path);
            $filterFail = $data->filter && $data->filter != $entity->type;

            if (!$entity->type || $filterFail) {
                continue;
            }

            $result[$entity->type][] = $entity;
        }

        return $result;
    }

    private static function createEntity(string $name, string $path): object
    {
        $realpath = realpath($path . DIRECTORY_SEPARATOR . $name);

        $entity = new stdClass;
        $entity->name = $name;
        $entity->path = $realpath;
        $entity->type = filetype($realpath);

        return $entity;
    }
}
