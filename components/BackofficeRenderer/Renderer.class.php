<?php 

class Renderer {
    protected array $_components;

    public function __construct() {
        $this->_components = [];
    }

    private function RequireClass(string $class) {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/BackofficeRenderer/{$class}.class.php");
    }
    
    /**
     * [Main function of Renderer]
     *
     * @return string
     * 
     */
    public function Render():string {
        $html = '';
        foreach($this->_components as $component) {
            $html .= $component->Render();
        }

        return $html;
    }

    /**
     * [Adds a h1 element to the page creation]
     *
     * @return H1
     * 
     */
    public function H1():H1 {
        $this->RequireClass('H1');
        $obj = new H1();

        $this->_components[] = $obj;
        
        return $obj;
    }

    public function StartDiv():StartDiv {
        $this->RequireClass('Div');
        $obj = new StartDiv();

        $this->_components[] = $obj;
        
        return $obj;
    }

    public function EndDiv():EndDiv {
        $this->RequireClass('Div');
        $obj = new EndDiv();

        $this->_components[] = $obj;
        
        return $obj;
    }
}