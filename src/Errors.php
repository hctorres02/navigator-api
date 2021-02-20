<?php

namespace HCTorres02\Navigator;

class Errors
{
    public static function dispatch(int $code, Entity $entity = null)
    {
        header("HTTP/1.1 {$code}");

        if ($entity) {
            echo json_encode($entity);
        }

        exit;
    }
}
