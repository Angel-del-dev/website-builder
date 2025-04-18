<?php 

class Button {
    public string $id;
    public string $class;
    public string $style;
    private string $caption;
    public bool $disabled;
    public function __construct() {
        $this->id = '';
        $this->class = '';
        $this->style = '';
        $this->caption = '';
        $this->disabled = false;
    }

    public function SetCaption(string $caption = '') {
        $this->caption = $caption;
    }

    public function Render() {
        $id = strlen(trim($this->id)) > 0 ? " id='{$this->id}' " : '';
        $class = strlen(trim($this->class)) > 0 ? " class='{$this->class}' " : '';
        $style = strlen(trim($this->style)) > 0 ? " style='{$this->style}' " : '';
        $disabled = $this->disabled ? ' disabled ' : '';
        return "
            <button {$id} {$class} {$style} {$disabled}>{$this->caption}</button>
        ";
    }
}