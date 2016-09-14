<?php
require_once("../../config.php");

$type = optional_param('type', '', PARAM_ALPHA);

switch ($type) {
    case 'reveal':
        include_once './reveal.php';
        break;
    case 'bespoke':
        include_once './bespoke.php';
        break;
    case 'impress':
        include_once './impress.php';
        break;
    case 'handouts':
        include_once './handouts.php';
        break;
    default:
        include_once './impress.php';
        break;
}
