<?php 

class Separator {
    private string $thickness;
    private string $color;
    private string $width;
    public function __construct(string $thickness = '1px', string $color = 'lightgray', string $width = '100%') {
        $this->thickness = $thickness;
        $this->color = $color;
        $this->width = $width;
    }

    public function SetThickness(string $thickness = ''):void {
        if(trim($thickness) === '') return;
        $this->thickness = $thickness;
    }
    public function SetColor(string $color = ''):void {
        if(trim($color) === '') return;
        $this->color = $color;
    }
    public function SetWidth(string $width = ''):void {
        if(trim($width) === '') return;
        $this->width = $width;
    }

    public function Render() {
        return "
            <div
                class='m-1' style='border: {$this->thickness} solid {$this->color}; width: {$this->width};'
            ></div>
        ";
    }
}