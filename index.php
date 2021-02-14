<?php

require "vendor/autoload.php";

use HCTorres02\Navigator\Browser;
use HCTorres02\Navigator\Filter;
use HCTorres02\Navigator\Transfer;
use HCTorres02\Navigator\Viewer;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://navigator-ui.gear.host');

$path = urldecode(filter_input(INPUT_GET, 'path') ?? __DIR__);
$mode = filter_input(INPUT_GET, 'mode');

switch ($mode) {
    default:
    case 'browser':
        $filter = Filter::make(filter_input(INPUT_GET, 'filter', FILTER_VALIDATE_INT));
        echo Browser::fetch($path, $filter);
        break;
    case 'viewer':
        echo Viewer::get($path);
        break;
    case 'download':
        Transfer::download($path);
        break;
}
