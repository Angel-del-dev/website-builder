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

    public function StartRender():string {
        $component = '';
        if($this->isDesign) {
            $component = " component='{$this->componentname}' ";
        }
        return "
            <div
                id='{$this->params->name}'
                {$component}
            >
        ";
    }

    public function EndRender():string {
        return "</div>";
    }
}