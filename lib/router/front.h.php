<?php

require_once("{$root_path}lib/router/router.class.php");

class FrontRouter extends Router{
    public function __construct() {
        parent::__construct();
    }

    public function Handle(array $uri) {
        print_r('TODO');
        exit;
    }
}