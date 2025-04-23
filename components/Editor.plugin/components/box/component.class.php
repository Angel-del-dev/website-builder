<?php 

class box {
    private stdClass $params;
    private bool $isDesign;
    private string $componentname;
    public function __construct(stdClass $params, bool $isDesign) {
        $this->params = $params;
        $this->isDesign = $isDesign;
        $this->componentname = 'box';
    }

    private function GetParamValue(string $key):string {
        return isset($this->params->$key) ? $this->params->$key : '';
    }

    public function GetControls():array {
        return [
            'name' => [ 'type' => 'text', 'label' => 'name', 'value' => $this->GetParamValue('name') ],
            'v-align' => [ 'type' => 'list', 'options' => [['name' => 'start', 'value' => 'justify-start'], ['name' => 'center', 'value' => 'justify-center'], ['name' => 'end', 'value' => 'justify-end'], ['name' => 'between', 'value' => 'justify-between'], ['name' => 'around', 'value' => 'justify-around'], ['name' => 'evenly', 'value' => 'justify-evenly']], 'label' => 'vertical', 'value' => $this->GetParamValue('caption') ],
            'h-align' => [ 'type' => 'list', 'options' => [['name' => 'start', 'value' => 'align-start'], ['name' => 'center', 'value' => 'align-center'], ['name' => 'end', 'value' => 'align-end'], ], 'label' => 'horizontal', 'value' => $this->GetParamValue('caption') ],
        ];
    }

    public function StartRender(int $position = 0):string {
        $component = '';
        if($this->isDesign) {
            $component_params = clone $this->params;
            unset($component_params->children);
            $component = sprintf(" properties='%s' component='{$this->componentname}' ", json_encode($component_params));
        }
        return "
            <div
                id='{$this->params->name}' position='{$position}'
                {$component}
            >
        ";
    }

    public function EndRender():string {
        return "</div>";
    }
}