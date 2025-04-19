<?php


class A {
    public string $id;
    public string $class;
    public string $style;
    protected string $href;
    protected string $caption;
    public function __construct(string $caption = '', string $href = '#') {
        $this->id = '';
        $this->class = '';
        $this->style = '';
        $this->href = $href;
        $this->caption = $caption;
    }

    public function SetLink(string $caption = '', string $href = '#'):void {
        $this->href = $href;
        $this->caption = $caption;
    }

    public function SetIcon(string $icon, string $size, string $type = 'solid') {
        $this->class .= " fa-{$type} fa-{$icon} fa-{$size} ";
    }

    public function Render():string {
        $id = strlen(trim($this->id)) > 0 ? "id='{$this->id}'" : '';
        $class = strlen(trim($this->class)) > 0 ? "class='{$this->class}'" : '';
        $style = strlen(trim($this->style)) > 0 ? "style='{$this->style}'" : '';
        $href = strlen(trim($this->href)) > 0 ? "href='{$this->href}'" : '';
        return "
            <a
                {$href} {$id} {$class} {$style}
            >{$this->caption}</a>
        ";
    }
}