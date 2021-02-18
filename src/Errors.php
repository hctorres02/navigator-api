<?php

namespace HCTorres02\Navigator;

class Errors
{
    public static function dispatch(int $code)
    {
        header("HTTP/1.1 {$code}");
        exit;
    }
}
