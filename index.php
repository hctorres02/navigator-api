<?php

require "vendor/autoload.php";

use HCTorres02\Navigator\{
    Browser,
    Helper,
    Transfer,
    Viewer,
    Errors
};

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://navigator-ui.gear.host');

$mode = urldecode(filter_input(INPUT_GET, 'mode'));
$drive = urldecode(filter_input(INPUT_GET, 'drive'));
$path = urldecode(filter_input(INPUT_GET, 'path'));

if (!$mode || !$drive) {
    header("HTTP/1.0 400");
    echo json_encode(Errors::BAD_REQUEST);

    return;
}

$pathWrapper = Helper::pathWrapper($drive, $path);
$realpath = realpath($pathWrapper);

if (!$realpath) {
    header("HTTP/1.0 404");
    echo json_encode(Errors::NOT_FOUND);

    return;
}

switch ($mode) {
    default:
    case 'browser':
        if (!Helper::canReadDir($realpath)) {
            header("HTTP/1.0 403");
            echo json_encode(Errors::FORBIDDEN);

            return;
        }

        $data = Browser::fetch($realpath);
        break;
    case 'viewer':
        if (
            !Helper::canReadFile($realpath)
            || !Helper::canViewFile($realpath)
        ) {
            header("HTTP/1.0 403");
            echo json_encode(Errors::FORBIDDEN);

            return;
        }

        $data = Viewer::get($realpath);
        break;
    case 'download':
        if (
            !Helper::canReadFile($realpath)
            || !Helper::canDownloadFile($realpath)
        ) {
            header("HTTP/1.0 403");
            echo json_encode(Errors::FORBIDDEN);

            return;
        }

        Transfer::download($realpath);
        return;
        break;
}

echo json_encode($data);
