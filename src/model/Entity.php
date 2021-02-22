<?php

namespace HCTorres02\Navigator\Model;

use stdClass;
use HCTorres02\Navigator\Core\{
    Browser,
    Transfer,
    Viewer,
    Writer
};

class Entity extends stdClass
{
    public $path;
    public $id;
    public $dirname;
    public $name;
    public $isDir;
    public $isDownloadable;
    public $isReadable;
    public $isWritable;
    public $data;

    public function __construct(string $path, string $data = null)
    {
        $path = realpath($path);
        $id = md5($path);
        $dirname = dirname($path);
        $name = basename($path);
        $isDir = is_dir($path);
        $isReadable = $isDir ? Browser::canRead($path) : Viewer::canView($path);
        $isDownloadable = !$isDir && $isReadable && Transfer::isDownloadable($path);
        $isWritable = $isDir ? is_writable($path) : Writer::canWrite($path);

        $this->path = $path;
        $this->id = $id;
        $this->dirname = $dirname;
        $this->name = $name;
        $this->isDir = $isDir;
        $this->isDownloadable = $isDownloadable;
        $this->isReadable = $isReadable;
        $this->isWritable = $isWritable;
        $this->data = $data;
    }
}
