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
    public $id;
    public $path;
    public $dirname;
    public $name;
    public $isDir;
    public $isDownloadable;
    public $isReadable;
    public $isWritable;
    public $size;
    public $data;

    public function __construct(string $path, string $data = null)
    {
        $id = md5($path . rand());
        $path = realpath($path);
        $dirname = dirname($path);
        $name = basename($path);
        $isDir = is_dir($path);
        $isReadable = $isDir ? Browser::canRead($path) : Viewer::canView($path);
        $isDownloadable = !$isDir && $isReadable && Transfer::isDownloadable($path);
        $isWritable = $isDir ? is_writable($path) : Writer::canWrite($path);
        $size = $isDir ? '-' : filesize($path);

        $this->id = $id;
        $this->path = $path;
        $this->dirname = $dirname;
        $this->name = $name;
        $this->isDir = $isDir;
        $this->isDownloadable = $isDownloadable;
        $this->isReadable = $isReadable;
        $this->isWritable = $isWritable;
        $this->size = $size;
        $this->data = $data;
    }
}
