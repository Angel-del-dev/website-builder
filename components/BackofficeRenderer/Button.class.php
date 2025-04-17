<?php 

class Button {
    public string $id;
    public string $class;
    private string $caption;
    public bool $disabled;
    public function __construct() {
        $this->id = '';
        $this->class = '';
        $this->caption = '';
        $this->disabled = false;
    }

    public function SetCaption(string $caption = '') {
        $this->caption = $caption;
    }

    public function Render() {
        $id = strlen(trim($this->id)) > 0 ? " id='{$this->id}' " : '';
        $class = strlen(trim($this->class)) > 0 ? " class='{$this->class}' " : '';
        $disabled = $this->disabled ? ' disabled ' : '';
        return "
            <button {$id} {$class} {$disabled}>{$this->caption}</button>
        ";
    }
}