<?php 

class Label {
    private string $caption;
    private bool $isBold;
    public function __construct(string $caption = '', bool $isBold = true) {
        $this->caption = $caption;
        $this->isBold = $isBold;
    }

    public function SetCaption(string $caption):void {
        $this->caption = $caption;
    }

    public function IsBold(bool $isBold = true):void {
        $this->isBold = $isBold;
    }

    public function Render():string {
        $font_weight = $this->isBold ? 'font-bold' : 'font-normal';
        return "
            <label class='w-100 {$font_weight}'>{$this->caption}</label>
        ";
    }
}