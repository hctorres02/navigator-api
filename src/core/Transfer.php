<?php

namespace HCTorres02\Navigator\Core;

class Transfer
{
    public const ALLOWED_DOWNLOAD = [
        'gif', 'jpg', 'jpeg', 'png', 'zip'
    ];

    public const ALLOWED_UPLOAD = [
        'gif', 'jpg', 'jpeg', 'png', 'zip'
    ];

    public static function isDownloadable(string $path): bool
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return in_array($extension, self::ALLOWED_DOWNLOAD);
    }

    public static function download(string $path): void
    {
        $filename = basename($path);
        $filesize = filesize($path);
        $headers = [
            'Cache-Control: must-revalidate',
            'Content-Description: File Transfer',
            "Content-Disposition: attachment; filename={$filename}",
            "Content-Length: {$filesize}",
            'Expires: 0',
            'Pragma: public',
        ];

        foreach ($headers as $key) {
            header($key);
        }

        readfile($path);
    }
}
