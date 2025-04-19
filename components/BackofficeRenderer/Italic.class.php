<?php

class I {
    public string $id;
    public string $class;
    public string $style;
    protected string $caption;
    public function __construct(string $caption = '') {
        $this->id = '';
        $this->class = '';
        $this->style = '';
        $this->caption = $caption;
    }

    public function SetCaption(string $caption):void {
        $this->caption = $caption;
    }

    public function Render():string {
        $id = strlen(trim($this->id)) > 0 ? "id='{$this->id}'" : '';
        $class = strlen(trim($this->class)) > 0 ? "class='{$this->class}'" : '';
        $style = strlen(trim($this->style)) > 0 ? "style='{$this->style}'" : '';
        return "
            <i
                {$id} {$class} {$style}
            >{$this->caption}</i>
        ";
    }
}

class Icon extends I {
    public function __construct() {
        parent::__construct();
    }

    public function SetIcon(string $icon, string $size, string $type = 'solid') {
        $this->class = " fa-{$type} fa-{$icon} fa-{$size} ";
    }
}