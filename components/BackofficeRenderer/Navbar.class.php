<?php

class Nav {
    public string $id;
    public string $class;
    public string $style;
    protected string $tag;
    public function __construct(string $id = '') {
        $this->id = $id;
        $this->class = '';
        $this->style = '';
        $this->tag = '';
    }

    public function Render():string {
        $id = strlen(trim($this->id)) > 0 ? "id='{$this->id}'" : '';
        $class = strlen(trim($this->class)) > 0 ? "class='{$this->class}'" : '';
        $style = strlen(trim($this->style)) > 0 ? "style='{$this->style}'" : '';
        return "
            <{$this->tag}
                {$id} {$class} {$style}
            >
        ";
    }
}

class StartNav extends Nav {
    public function __construct(string $id = '') {
        parent::__construct($id);
        $this->tag = 'nav';
    }
}

class EndNav extends Nav {
    public function __construct() {
        parent::__construct();
        $this->tag = '/nav';
    }
}