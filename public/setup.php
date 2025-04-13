<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/../lib/functions.inc.php");
$root_path = GetRootPath();

require_once("{$root_path}/lib/files/Parse.php");
$cfg = Parse::CFG();

// Start Constant definition

define('IS_DEPLOY', $cfg->configuration->deploy === 'On');
define('IS_INITIAL', $cfg->configuration->initial === 'On');
/*
    @constant REQUESTED_URI
    [Using $_SERVER['REDIRECT_URL'] to get the path without query parameters]
*/
define('REQUESTED_URI', $_SERVER['REDIRECT_URL']);
// TODO Get the backoffice prefix from the DB
define('BACKOFFICE_PREFIX', '/panel');

// End Constant definition