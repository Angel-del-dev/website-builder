<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/../lib/functions.inc.php");

$root_path = GetRootPath();
require_once("{$root_path}/lib/files/Parse.php");
$cfg = Parse::CFG();