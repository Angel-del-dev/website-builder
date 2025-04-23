<?php 

class paragraph {
    private stdClass $params;
    private bool $isDesign;
    private string $componentname;
    public function __construct(stdClass $params, bool $isDesign) {
        $this->params = $params;
        $this->isDesign = $isDesign;
        $this->componentname = 'paragraph';
    }

    private function GetParamValue(string $key):string {
        return isset($this->params->$key) ? $this->params->$key : '';
    }

    public function GetControls():array {
        return [
            'name' => [ 'type' => 'text', 'label' => 'name', 'value' => $this->GetParamValue('name') ],
            'caption' => [ 'type' => 'text', 'label' => 'caption', 'value' => $this->GetParamValue('caption') ],
        ];
    }
    

    public function StartRender():string {
        $component = '';
        if($this->isDesign) {
            $component_params = clone $this->params;
            unset($component_params->children);
            $component = sprintf(" properties='%s' component='{$this->componentname}' ", json_encode($component_params));
        }
        return "
            <p
                id='{$this->params->name}'
                {$component}
            >
                {$this->params->caption}
        ";
    }

    public function EndRender():string {
        return "</p>";
    }
}