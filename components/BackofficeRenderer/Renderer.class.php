<?php 

class Renderer {
    protected array $_components;

    public function __construct() {
        $this->_components = [];
    }

    private function RequireClass(string $file, string $class):mixed {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/BackofficeRenderer/{$file}.class.php");
        $obj = new $class();
        $this->_components[] = $obj;
        return $obj;
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

    public function H1(string $caption):H1 {
        $obj = $this->RequireClass('Titles', 'H1');
        $obj->SetText($caption);
        return $obj;
    }
    public function H2(string $caption):H2 {
        $obj = $this->RequireClass('Titles', 'H2');
        $obj->SetText($caption);
        return $obj;
    }
    public function H3(string $caption):H3 {
        $obj = $this->RequireClass('Titles', 'H3');
        $obj->SetText($caption);
        return $obj;
    }
    public function H4(string $caption):H4 {
        $obj = $this->RequireClass('Titles', 'H4');
        $obj->SetText($caption);
        return $obj;
    }
    public function H5(string $caption):H5 {
        $obj = $this->RequireClass('Titles', 'H5');
        $obj->SetText($caption);
        return $obj;
    }
    public function H6(string $caption):H6 {
        $obj = $this->RequireClass('Titles', 'H6');
        $obj->SetText($caption);
        return $obj;
    }

    public function StartDiv():StartDiv {
        return $this->RequireClass('Div', 'StartDiv');
    }

    public function EndDiv():EndDiv {
        return $this->RequireClass('Div', 'EndDiv');
    }

    public function StartForm():StartForm {
        return $this->RequireClass('Form', 'StartForm');
    }
    public function EndForm():EndForm {
        return $this->RequireClass('Form', 'EndForm');
    }

    public function Text(string $id = '', string $default_value = ''):Text {
        $obj = $this->RequireClass('Input', 'Text');
        $obj->id = $id;
        $obj->SetValue($default_value);
        return $obj;
    }

    public function Password(string $id = '', string $default_value = ''):Password {
        $obj = $this->RequireClass('Input', 'Password');
        $obj->id = $id;
        $obj->SetValue($default_value);
        return $obj;
    }

    public function Hidden(string $id = '', string $default_value = ''):Hidden {
        $obj = $this->RequireClass('Input', 'Hidden');
        $obj->id = $id;
        $obj->SetValue($default_value);
        return $obj;
    }

    public function Button(string $id, string $caption):Button {
        $obj = $this->RequireClass('Button', 'Button');
        $obj->id = $id;
        $obj->SetCaption($caption);
        return $obj;
    }
}