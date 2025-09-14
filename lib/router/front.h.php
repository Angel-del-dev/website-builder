<?php

require_once("{$root_path}lib/router/router.class.php");

class FrontRouter extends Router{
    public function __construct() {
        parent::__construct();
    }

    public function Handle(array $uri) {
        header(sprintf('Location: /%s/home', BACKOFFICE_PREFIX));
        exit;
    }
}