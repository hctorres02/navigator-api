<?php

namespace HCTorres02\Navigator;

use stdClass;

class Viewer
{
    public static function get(string $path): string
    {
        $data = new stdClass;
        $data->path = realpath($path);

        if (Helper::canReadFile($data->path)) {
            ob_start();
            $data->contents = file_get_contents($data->path);
            $data->isWritable = is_writable($data->path);
            ob_flush();
        }

        return json_encode($data);
    }
}
