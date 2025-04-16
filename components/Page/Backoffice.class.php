<?php

class BackofficePage {
    private string $_method;
    protected string|stdClass|bool $_result;
    public function __construct() {
        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_result = false;
    }

    public function Request():false|string {
        if($this->_result === null) return false;
        return $this->_method === 'POST' ? json_encode($this->_result) : $this->_result;
    }
}