<?php

class Router {
    protected stdClass $_result;
    public function __construct() {
        $this->_result = new stdClass();
    }

    /**
     * [Returns the result from the specific router]
     *
     * @return stdClass
     * 
     */
    public function Invoke():stdClass {
        return $this->_result;
    }
}