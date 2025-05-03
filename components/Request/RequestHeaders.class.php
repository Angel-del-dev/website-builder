<?php

class RequestHeader {
    private string $name;
    private string $value;
    public function __construct(string $name, string $value) { 
        $this->name = $name;
        $this->value = $value;
    }    

    public function GetName():string { return $this->name; }
    public function GetValue():string { return $this->value; }
}