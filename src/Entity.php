<?php

namespace HCTorres02\Navigator;

use stdClass;

class Entity extends stdClass
{
    public $path;
    public $dirname;
    public $name;
    public $isDir;
    public $isDownloadable;
    public $isReadable;
    public $isWritable;
    public $data;

    public function __construct(string $drive, string $path = NULL, bool $ignorePath = FALSE)
    {
        $originalPath = Helper::pathWrapper($drive, $path, $ignorePath);
        $realpath = realpath($originalPath);
        $dirname = dirname($realpath);
        $name = basename($realpath);
        $isDir = is_dir($realpath);
        $isReadable = $isDir ? Browser::canRead($realpath) : Viewer::canView($realpath);
        $isDownloadable = !$isDir && $isReadable && Transfer::isDownloadable($realpath);
        $isWritable = is_writable($this->path);

        $this->path = $realpath;
        $this->dirname = $dirname;
        $this->name = $name;
        $this->isDir = $isDir;
        $this->isDownloadable = $isDownloadable;
        $this->isReadable = $isReadable;
        $this->isWritable = $isWritable;
    }
}
