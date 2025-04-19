<?php 

class StartDiv {
    public string $id;
    public string $class;
    public string $style;
    public string $attributes;
    public function __construct() {
        $this->id = '';
        $this->class = '';
        $this->style = '';
        $this->attributes = '';
    }

    public function AddAttribute(string $key, string $value):void {
        $this->attributes .= sprintf(" %s='%s' ", $key, $value);
    }

    public function Render() {
        $id = strlen(trim($this->id)) > 0 ? "id='{$this->id}'" : '';
        $class = strlen(trim($this->class)) > 0 ? "class='{$this->class}'" : '';
        $style = strlen(trim($this->style)) > 0 ? "style='{$this->style}'" : '';
        return "<div {$id} {$class} {$style} {$this->attributes} >";
    }
}

class EndDiv {
    public function __construct() {

    }

    public function Render() {
        return "</div>";
    }
}