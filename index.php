<?php

require 'vendor/autoload.php';

use HCTorres02\Navigator\{
    Browser,
    Entity,
    Errors,
    Transfer,
    Viewer,
    Writer,
};

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: https://navigator-ui.gear.host');

define('GET', 'GET');
define('POST', 'POST');
define('PUT', 'PUT');
define('DELETE', 'DELETE');

$methods = [GET, POST, DELETE, PUT];
$request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

if (!in_array($request_method, $methods)) {
    Errors::dispatch(405);
}

$drive = urldecode(filter_input(INPUT_GET, 'drive'));
$path = urldecode(filter_input(INPUT_GET, 'path'));
$download_mode = filter_input(INPUT_GET, 'download', FILTER_VALIDATE_BOOLEAN);

if (!$drive) {
    Errors::dispatch(400);
}

$entity = new Entity($drive, $path);

if (!$entity->path) {
    Errors::dispatch(404);
}

if (
    !$entity->isReadable
    || !$entity->isDownloadable && $download_mode
    || !$entity->isWritable && $request_method == POST
) {
    Errors::dispatch(403);
}

switch ($request_method) {
    case GET:
        if ($download_mode) {
            Transfer::download($entity->path);
            return;
        }

        $entity->data = $entity->isDir
            ? Browser::fetch($entity->path)
            : Viewer::get($entity->path);

        break;
    case POST:

        // TODO
        $entity->data = ['fake data'];

        break;
    case PUT:

        // TODO
        $entity->data = ['fake data'];

        break;
    case DELETE:

        // TODO
        $entity->data = ['fake data'];

        break;
}

echo json_encode($entity);
