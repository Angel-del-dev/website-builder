<?php

class Container {
    protected string $tag;
    public string $id;
    public string $class;
    public string $style;
    public function __construct() {
        $this->tag = '';
        $this->id = '';
        $this->class = '';
        $this->style = '';
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

class StartAside extends Container {
    public function __construct() {
        parent::__construct();
        $this->tag = 'aside';
    }
}

class EndAside extends Container {
    public function __construct() {
        parent::__construct();
        $this->tag = '/aside';
    }
}

class StartSection extends Container {
    public function __construct() {
        parent::__construct();
        $this->tag = 'section';
    }
}

class EndSection extends Container {
    public function __construct() {
        parent::__construct();
        $this->tag = '/section';
    }
}

class StartArticle extends Container {
    public function __construct() {
        parent::__construct();
        $this->tag = 'article';
    }
}

class EndArticle extends Container {
    public function __construct() {
        parent::__construct();
        $this->tag = '/article';
    }
}