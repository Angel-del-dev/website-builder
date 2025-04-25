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
            'h-align' => [ 'type' => 'list', 'options' => $this->transform_to_list_items(['start', 'justify-start'], ['center', 'justify-center'], ['end', 'justify-end'], ['between', 'justify-between'], ['around', 'justify-around'], ['evenly', 'justify-evenly']), 'label' => 'horizontal', 'value' => $this->GetParamValue('horizontal') ],
            'v-align' => [ 'type' => 'list', 'options' => $this->transform_to_list_items(['start', 'align-start'], ['center', 'align-center'], ['end', 'align-end']), 'label' => 'vertical', 'value' => $this->GetParamValue('vertical') ],
            'gap' => [ 'label' => 'gap', 'type' => 'list', 'options' => $this->transform_to_list_items(['0', 'gap-0'], ['1', 'gap-1'], ['2', 'gap-2'], ['3', 'gap-3'], ['4', 'gap-4'], ['5', 'gap-5']), 'value' =>  $this->GetParamValue('gap')],
            'padding' => [ 'label' => 'padding', 'type' => 'list', 'options' => $this->transform_to_list_items(['0', 'p-0'], ['1', 'p-1'], ['2', 'p-2'], ['3', 'p-3'], ['4', 'p-4'], ['5', 'p-5']), 'value' => $this->GetParamValue('padding') ],
            'direction' => [ 'label' => 'direction', 'type' => 'list', 'options' => $this->transform_to_list_items(['row', 'flex-row'], ['column', 'flex-column']), 'value' => $this->GetParamValue('direction') ]
        ];
    }

    private function transform_to_list_items(...$items):array {
        $result = [];

        foreach($items as $item) {
            $result[] = [ 'name' => $item[0], 'value' => $item[1] ];
        }

        return $result;
    }

    private function property_to_class():string {
        $class = '';
        $available = ['vertical', 'horizontal', 'gap', 'padding', 'direction'];
        
        foreach($available as $className) {
            if(trim($className) === '') continue;
            if(!isset($this->params->$className) || $this->params->$className === '') continue;
            $class .= " {$this->params->$className} ";
        }
        return $class;
    }

    public function StartRender(int $position = 0):string {
        $component = '';
        $class = $this->property_to_class();
        if($this->isDesign) {
            $component_params = clone $this->params;
            unset($component_params->children);
            $component = sprintf(" properties='%s' component='{$this->componentname}' ", json_encode($component_params));
        }
        if($class !== '') $class = " flex {$class} ";
        $class = " class='w-100 flex {$class}' ";
        return "
            <div
                id='{$this->params->name}' position='{$position}' {$class}
                {$component}
            >
        ";
    }

    public function EndRender():string {
        return "</div>";
    }
}