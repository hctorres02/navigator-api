<?php

require 'vendor/autoload.php';

use HCTorres02\Navigator\{
    HttpStatus,
    Helper
};
use HCTorres02\Navigator\Model\Entity;
use HCTorres02\Navigator\Core\{
    Browser,
    Transfer,
    Viewer,
    Writer
};

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

define('DRIVE', urldecode(filter_input(INPUT_GET, 'drive')));
define('PATH', urldecode(filter_input(INPUT_GET, 'path')));
define('REQUEST_METHOD', filter_input(INPUT_SERVER, 'REQUEST_METHOD'));

if (!DRIVE) {
    HttpStatus::dispatch(400);
}

if (!in_array(REQUEST_METHOD, Helper::ALLOWED_METHODS)) {
    HttpStatus::dispatch(405);
}

define('DOWNLOAD_MODE', REQUEST_METHOD == GET && filter_input(INPUT_GET, 'download', FILTER_VALIDATE_BOOLEAN));
define('CREATE_MODE', REQUEST_METHOD == POST);
define('PUT_MODE', REQUEST_METHOD == PUT);
define('DELETE_MODE', REQUEST_METHOD == DELETE);

$pathWrapper = Helper::pathWrapper(DRIVE, PATH);
$entity = new Entity($pathWrapper);

if (CREATE_MODE) {
    if ($entity->path) {
        HttpStatus::dispatch(409);
    }

    $create_type = filter_input(INPUT_GET, 'type');

    if (
        !PATH
        || !$create_type
        || !in_array($create_type, Helper::ALLOWED_CREATE_TYPES)
    ) {
        HttpStatus::dispatch(400);
    }

    $entity->path = $pathWrapper;
    $entity->isReadable = Browser::canRead($pathWrapper, true);
    $entity->isDir = Helper::typeIsDirectory($create_type);
}

if (!$entity->path) {
    HttpStatus::dispatch(404);
}

if (
    !$entity->isReadable
    || DOWNLOAD_MODE && !$entity->isDownloadable
    || PUT_MODE && !$entity->isWritable
) {
    HttpStatus::dispatch(403);
}

switch (REQUEST_METHOD) {
    case GET:
        if (DOWNLOAD_MODE) {
            Transfer::download($entity->path);
            return;
        }

        $entity->data = $entity->isDir
            ? Browser::fetch($entity->path, ['.', '..'])
            : Viewer::get($entity->path);

        break;
    case POST:
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$entity->isDir && (!$data || !isset($data['data']))) {
                HttpStatus::dispatch(400);
            }

            if (!$entity->isDir) {
                $entity->data = $data['data'];
            }

            if (Writer::create($entity)) {
                $created_entity = new Entity($entity->path, $entity->data);

                HttpStatus::dispatch(201, $created_entity);
            }

            HttpStatus::dispatch(500);
        } catch (Exception $e) {
            HttpStatus::dispatch(409);
        }

        break;
    case PUT:
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data || !isset($data['data'])) {
                HttpStatus::dispatch(400);
            }

            $entity->data = $data['data'];
            Writer::put($entity);

            HttpStatus::dispatch(204);
        } catch (Exception $e) {
            HttpStatus::dispatch(409);
        }

        break;
    case DELETE:
        try {
            // TODO
            $entity->data = ['fake data'];

            HttpStatus::dispatch(204);
        } catch (Exception $e) {
            HttpStatus::dispatch(409);
        }

        break;
}

echo json_encode($entity);
