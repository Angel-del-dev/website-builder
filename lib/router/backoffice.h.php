<?php

require_once("{$root_path}lib/router/router.class.php");

class BackofficeRouter extends Router{
    private string $_base_route;
    private string $_route;
    public function __construct() {
        parent::__construct();
        $this->_base_route = sprintf('%s/../%s', $_SERVER['DOCUMENT_ROOT'], 'backoffice');
        $this->_route = $this->_base_route;
    }

    /**
     * [Description for LoopDirStructure]
     *
     * @param array $uri
     * 
     * @return mixed
     * 
     */
    private function LoopDirStructure(array $uri):mixed {
        // Assumming /backoffice exists
        /*
            Remove the first index due to 'backoffice' not necessarily matching
            with the route
        */
        array_shift($uri);
        $found = true;
        foreach($uri as $uri_piece) {
            $uri_piece = trim($uri_piece);
            if($uri_piece === '') continue;
            $mockup_route = sprintf('%s/%s', $this->_route, $uri_piece);
            if(!is_dir($mockup_route)) {
                $found = false;
                break;
            }
            $this->_route = $mockup_route;
        }
        
        if(!$found) return false;
        
        require_once(sprintf('%s/index.h.php', $this->_route));
        return new Page();
    }

    /**
     * [Description for RedirectNotFound]
     *
     * @return void
     * 
     */
    private function RedirectNotFound():void {
        header(sprintf('Location: /%s/%s', BACKOFFICE_PREFIX, 'not_found'));
    }

    /**
     * [Description for Handle]
     *
     * @param array $uri
     * 
     * @return [type]
     * 
     */
    public function Handle(array $uri):void {
        $class = $this->LoopDirStructure($uri);
        if($class === false) {
            $this->RedirectNotFound();
            return;
        }

        $method = ucfirst($_SERVER['REQUEST_METHOD']);
        $class->$method();
        $result = $class->Request();
        if($result === false) {
            $this->RedirectNotFound();
            return;
        }

        $this->_result->type = 'string';
        $this->_result->content = $result;
    }
}