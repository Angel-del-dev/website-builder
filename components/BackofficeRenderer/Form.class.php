<?php

class StartForm {
    public string $class;
    public string $method;
    public string $action;
    public string $id;
    public function __construct() {
        $this->class = ' w-100 h-100 ';
        $this->method = 'POST';
        $this->action = '';
        $this->id = '';
    }

    public function Render():string {
        if(trim($this->action === '')) {
            Log::Entry('Given form does not have an action');
            throw new Error('All forms must have an action');
        } 
        $id = strlen(trim($this->id)) > 0 ? "id='{$this->id}'" : '';
        $class = strlen(trim($this->class)) > 0 ? "class='{$this->class}'" : '';
        return "<form {$id} {$class} action='{$this->action}' method='{$this->method}'>";
    }
}

class EndForm {
    public function __construct() {}

    public function Render():string {
        return "</form>";
    }
}