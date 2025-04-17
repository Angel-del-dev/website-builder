<?php 

class H1 {
    private string $_text;
    public string $class;
    public string $style;
    public function __construct() {
        $this->_text = '';
        $this->class = ' p-0 m-0 ';
        $this->style = '';
    }

    public function SetText(string $text) {
        $this->_text = $text;
    }

    public function Render():string {
        $class = strlen(trim($this->class)) > 0 ? "class='{$this->class}'" : '';
        $style = strlen(trim($this->style)) > 0 ? "style='{$this->style}'" : '';
        return "
            <h1 {$class} {$style}>
                {$this->_text}
            </h1>
        ";
    }
}