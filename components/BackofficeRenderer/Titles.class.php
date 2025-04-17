<?php 

class Title {
    private string $_text;
    public string $class;
    public string $style;
    protected string $tag;
    public function __construct() {
        $this->_text = '';
        $this->class = ' p-0 m-0 ';
        $this->style = '';
        $this->tag = 'h1';
    }

    public function SetText(string $text) {
        $this->_text = $text;
    }

    public function Render():string {
        $class = strlen(trim($this->class)) > 0 ? "class='{$this->class}'" : '';
        $style = strlen(trim($this->style)) > 0 ? "style='{$this->style}'" : '';
        return "
            <{$this->tag} {$class} {$style}>
                {$this->_text}
            </{$this->tag}>
        ";
    }
}

class H1 extends Title {
    public function __construct(string $caption = ''){
        parent::__construct();
        $this->tag = 'h1';
        $this->SetText($caption);
    }
}
class H2 extends Title {
    public function __construct(string $caption = ''){
        parent::__construct();
        $this->tag = 'h2';
        $this->SetText($caption);
    }
}
class H3 extends Title {
    public function __construct(string $caption = ''){
        parent::__construct();
        $this->tag = 'h3';
        $this->SetText($caption);
    }
}
class H4 extends Title {
    public function __construct(string $caption = ''){
        parent::__construct();
        $this->tag = 'h4';
        $this->SetText($caption);
    }
}
class H5 extends Title {
    public function __construct(string $caption = ''){
        parent::__construct();
        $this->tag = 'h5';
        $this->SetText($caption);
    }
}
class H6 extends Title {
    public function __construct(string $caption = ''){
        parent::__construct();
        $this->tag = 'h6';
        $this->SetText($caption);
    }
}