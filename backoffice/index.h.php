<?php

require_once(sprintf('%s/../components/Page/Backoffice.class.php', $_SERVER['DOCUMENT_ROOT']));
require_once(sprintf('%s/../lib/translations/Translation.class.php', $_SERVER['DOCUMENT_ROOT']));

class Page extends BackofficePage {
    public function __construct() {
        parent::__construct();
    }

    public function Get() {
        $this->AddTitle(Translation::Get('backoffice', 'login-title'));
        $this->AddResource('style', '/css/backoffice/Login.css');
        $this->AddResource('script', '/js/backoffice/Login.js');
    }

    public function Post() {
        // TODO Handle post Login
    }
}