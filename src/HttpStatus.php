<?php

namespace HCTorres02\Navigator;

use HCTorres02\Navigator\Model\Entity;

class HttpStatus
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
