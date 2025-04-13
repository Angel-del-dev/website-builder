<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/setup.php");

// TODO Handle if BACKOFFICE_PREFIX is null

$uri_array = explode('/', substr(REQUESTED_URI, 1)); // Removing the first '/'

$router = null;
if($uri_array[0] === BACKOFFICE_PREFIX) {
    require_once("{$root_path}lib/router/backoffice.h.php");
    $router = new BackofficeRouter();
} else {
    require_once("{$root_path}lib/router/front.h.php");
    $router = new FrontRouter();
}

$router->Handle();
$result = $router->Invoke();

// TODO Handle the result based on type -> json|xml, html