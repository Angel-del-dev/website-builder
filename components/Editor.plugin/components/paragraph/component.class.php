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

    public function GetAllowedParams():array {
        return [

        ];
    }

    public function StartRender():string {
        $component = '';
        if($this->isDesign) {
            $component = " component='{$this->componentname}' ";
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