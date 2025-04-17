<?php 

class  Select {
  public string $id;
  public string $class;
  public string $value;
  private array $options;
  public string $style;
  public function __construct(string $id = '', string $value = '') {
    $this->id = $id; 
    $this->class = ' fancy-select ';
    $this->style = '';
    $this->value = $value;
    $this->options = [];
  }
  public function SetValue(string $value):void {
    $this->value = $value;
  }

  public function Add(string $key, string $value):void {
    $option = new stdClass;
    $option->key = $key;
    $option->value = $value;

    $this->options[] = $option;
  }

  public function Render():string {
    $options = '';
    foreach($this->options as $option) {
      $selected = $this->value === $option->key ? ' selected ' : '';
      $options .= " <option value='{$option->key}' {$selected}>{$option->value}</option> ";
    }
    $id = strlen(trim($this->id)) > 0 ? "id='{$this->id}'" : '';
    $class = strlen(trim($this->class)) > 0 ? "class='{$this->class}'" : '';
    $style = strlen(trim($this->style)) > 0 ? "style='{$this->style}'" : '';
    return "
      <select
        {$id} {$class} {$style}
      >
        {$options}
      </select>
    ";
  }
}
