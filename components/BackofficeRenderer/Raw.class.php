<?php
class Raw {
    private string $html;
    public function __construct(string $html = '') {
        $this->html = $html;
    }

    public function SetHtml(string $html):void {
        $this->html = $html;
    }

    public function Render():string {
        return $this->html;
    }
}