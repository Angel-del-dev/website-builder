<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/setup.php");

$uri_array = explode('/', substr(REQUESTED_URI, 1)); // Removing the first '/'

$router = null;
if($uri_array[0] === BACKOFFICE_PREFIX) {
    require_once("{$root_path}lib/router/backoffice.h.php");
    $router = new BackofficeRouter();
} else {
    require_once("{$root_path}lib/router/front.h.php");
    $router = new FrontRouter();
}

$router->Handle($uri_array);
$result = $router->Invoke();

$final_result = '';
switch($result->type) {
    case 'string':
        $final_result = $result->content;
    break;
    default:
        Header('Location: /not_found');
    break;
}

echo $final_result;