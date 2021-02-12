<?php

namespace HCTorres02\Navigator;

class Filter
{
    public const ANY = NULL;
    public const DIRECTORIES = 'dir';
    public const FILES = 'file';

    public static function make(?int $input): ?string
    {
        return [
            self::ANY,
            self::DIRECTORIES,
            self::FILES
        ][$input] ?? self::ANY;
    }
}
