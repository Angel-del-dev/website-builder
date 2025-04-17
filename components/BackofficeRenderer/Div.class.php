<?php 

class StartDiv {
    public string $id;
    public string $class;
    public string $style;
    public function __construct() {
        $this->id = '';
        $this->class = '';
        $this->style = '';
    }

    public function Render() {
        $id = strlen(trim($this->id)) > 0 ? "id='{$this->id}'" : '';
        $class = strlen(trim($this->class)) > 0 ? "class='{$this->class}'" : '';
        $style = strlen(trim($this->style)) > 0 ? "style='{$this->style}'" : '';
        return "<div {$id} {$class} {$style} >";
    }
}

class EndDiv {
    public function __construct() {

    }

    public function Render() {
        return "</div>";
    }
}