<?php

namespace HCTorres02\Navigator;

class Errors
{
    public const BAD_REQUEST = [
        'status' => 400,
        'statusText' => 'Bad request'
    ];

    public const FORBIDDEN = [
        'status' => 403,
        'statusText' => 'Forbidden'
    ];

    public const NOT_FOUND = [
        'status' => 404,
        'statusText' => 'Not found'
    ];
}
