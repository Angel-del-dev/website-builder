<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/Request/Request.class.php");

class Media extends Request {
    public function __construct() {
        parent::__construct();
        $this->Setup();
    }

    private function Setup() {
        $params = Parse::CFG()->api;
        if(!isset($params->media_user) || $params->media_user === '') throw new Exception('Media api is not properly configured');
        if(!isset($params->media_password) || $params->media_password === '') throw new Exception('Media api is not properly configured');

        $this->SetDomain($params->media);
        $this->SetUser($params->media_user);
        $this->SetPassword($params->media_password);
    }
}