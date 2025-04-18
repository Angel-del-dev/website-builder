<?php
session_start();

require_once("{$_SERVER['DOCUMENT_ROOT']}/../lib/functions.inc.php");
$root_path = GetRootPath();

require_once("{$root_path}/lib/session/Auth.class.php");
Auth::Setup();

require_once("{$root_path}/lib/files/Parse.class.php");
$cfg = Parse::CFG();

require_once("{$root_path}/lib/log/Log.class.php");

// Start Constant definition

define('IS_DEPLOY', $cfg->configuration->deploy === 'On');
define('IS_INITIAL', $cfg->configuration->initial === 'On');

define('REQUESTED_URI', explode('?', $_SERVER['REQUEST_URI'])[0]);

require_once("{$root_path}/components/Configuration/Configuration.class.php");

$_prefix = 'panel';
if(!IS_INITIAL) {
    $_prefix_db = Configuration::Get('BACKOFFICE_PREFIX');
    if($_prefix_db !== '') $_prefix = $_prefix_db;
}

define('BACKOFFICE_PREFIX', $_prefix);

// End Constant definition