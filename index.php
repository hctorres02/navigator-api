<?php

require 'vendor/autoload.php';

use HCTorres02\Navigator\{
    Entity,
    Errors,
    Helper
};
use HCTorres02\Navigator\Core\{
    Browser,
    Transfer,
    Viewer,
    Writer
};

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: https://navigator-ui.gear.host');

define('DRIVE', urldecode(filter_input(INPUT_GET, 'drive')));
define('PATH', urldecode(filter_input(INPUT_GET, 'path')));
define('REQUEST_METHOD', filter_input(INPUT_SERVER, 'REQUEST_METHOD'));

if (!DRIVE) {
    Errors::dispatch(400);
}

if (!in_array(REQUEST_METHOD, Helper::ALLOWED_METHODS)) {
    Errors::dispatch(405);
}

define('DOWNLOAD_MODE', REQUEST_METHOD == GET && filter_input(INPUT_GET, 'download', FILTER_VALIDATE_BOOLEAN));
define('CREATE_MODE', REQUEST_METHOD == POST);
define('PUT_MODE', REQUEST_METHOD == PUT);
define('DELETE_MODE', REQUEST_METHOD == DELETE);

$pathWrapper = Helper::pathWrapper(DRIVE, PATH);
$entity = new Entity($pathWrapper);

if (CREATE_MODE) {
    if ($entity->path) {
        Errors::dispatch(409);
    }

    $create_type = filter_input(INPUT_GET, 'type');

    if (!PATH || !$create_type || !in_array($create_type, Helper::ALLOWED_CREATE_TYPES)) {
        Errors::dispatch(400);
    }

    $entity->path = $pathWrapper;
    $entity->isReadable = Browser::canRead($pathWrapper, true);
    $entity->isDir = Helper::typeIsFolder($create_type);
}

if (!$entity->path) {
    Errors::dispatch(404);
}

if (
    !$entity->isReadable
    || DOWNLOAD_MODE && $entity->isDir
    || PUT_MODE && !$entity->isWritable
) {
    Errors::dispatch(403);
}

switch (REQUEST_METHOD) {
    case GET:
        if (DOWNLOAD_MODE) {
            Transfer::download($entity->path);
            return;
        }

        $entity->data = $entity->isDir
            ? Browser::fetch($entity->path)
            : Viewer::get($entity->path);

        break;
    case POST:
        try {
            // TODO
            if (Writer::create($entity)) {
                $entity = new Entity($entity->path);
            }

            Errors::dispatch(201);
        } catch (Exception $e) {
            Errors::dispatch(409);
        }

        break;
    case PUT:
        try {
            $data = json_decode(file_get_contents('php://input'));
            Writer::put($data);

            Errors::dispatch(204);
        } catch (Exception $e) {
            Errors::dispatch(409);
        }

        break;
    case DELETE:
        try {
            // TODO
            $entity->data = ['fake data'];

            Errors::dispatch(204);
        } catch (Exception $e) {
            Errors::dispatch(409);
        }

        break;
}

echo json_encode($entity);
