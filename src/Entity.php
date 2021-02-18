<?php

namespace HCTorres02\Navigator;

use stdClass;

class Entity extends stdClass
{
    public $originalPath;
    public $path;
    public $isDir;
    public $isDownloadable;
    public $isReadable;
    public $isWritable;
    public $data;

    public function __construct(string $drive, string $path)
    {
        $this->originalPath = Helper::pathWrapper($drive, $path);
        $this->path = realpath($this->originalPath);
        $this->isDir = is_dir($this->path);
        $this->isDownloadable = !$this->isDir && Transfer::isDownloadable($this->path);
        $this->isReadable = is_readable($this->path);
        $this->isWritable = is_writable($this->path);
    }
}
