<?php 

class Input {
    public string $id;
    public string $class;
    public string $style;
    private string|bool $value;
    private string $type;
    protected null|string $placeholder;
    public bool $readonly;
    public bool $disabled;
    public int $maxlength;
    public function __construct(string $id, string|bool $value) {
        $this->id = $id;
        $this->class = ' pretty-input ';
        $this->style = '';
        $this->value = $value;
        $this->type = 'text';
        $this->placeholder = null;
        $this->disabled = false;
        $this->readonly = false;
        $this->maxlength = 0;
    }

    protected function Type(string $type = 'text') {
        $this->type = $type;
    }

    public function SetPlaceholder(string $placeholder):void {
        $this->placeholder = $placeholder;
    }

    public function SetValue(bool|string $value):void {
        $this->value = $value;
    }

    public function Render() {
        $id = strlen(trim($this->id)) > 0 ? "id='{$this->id}'" : '';
        $class = strlen(trim($this->class)) > 0 ? "class='{$this->class}'" : '';
        $style = strlen(trim($this->style)) > 0 ? "style='{$this->style}'" : '';
        $placeholder = is_null($this->placeholder) ? '' : " placeholder='{$this->placeholder}' ";
        $value = '';
        $disabled = $this->disabled ? ' disabled ' : '';
        $readonly = $this->readonly ? ' readonly ' : '';
        $maxlength = $this->maxlength > 0 ? " maxlength='{$this->maxlength}' " : '';

        if(in_array(strtoupper($this->type), ['CHECKBOX', 'RADIO'])) {
            $value = $this->value ? " checked " : '';
        } else {
            if(strlen(trim($this->value)) > 0) $value = " value='{$this->value}' ";
        }

        return "
            <input
                type='{$this->type}'
                {$id} {$class} {$style} {$placeholder}
                {$value} {$readonly} {$disabled} {$maxlength}
            />
        ";
    }
}

class Text extends Input {
    public function __construct(string $id = '', string $default_value = '') {
        parent::__construct($id, $default_value);
        $this->class .= " w-100 ";
        $this->Type('text');
    }
}

class Password extends Input {
    public function __construct(string $id = '', string $default_value = '') {
        parent::__construct($id, $default_value);
        $this->class .= " w-100 ";
        $this->Type('password');
    }
}

class Hidden extends Input {
    public function __construct(string $id = '', string $default_value = '') {
        parent::__construct($id, $default_value);
        $this->Type('hidden');
    }
}
