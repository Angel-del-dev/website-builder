<?php

class BackofficePage {
    private string $_method;
    private array $_resources;
    private bool $_import_default_styles;
    private string $_title;

    protected string|stdClass|bool $_result;
    public function __construct() {
        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_result = false;
        $this->_resources = [];
        $this->_import_default_styles = true;
        $this->_title = '';
    }

    /**
     * [Add a title for the page]
     *
     * @param string $title
     * 
     * @return void
     * 
     */
    public function AddTitle(string $title):void {
        $this->_title = $title;
    }

    /**
     * [Toggles the automatic include of the default css spreadsheet]
     *
     * @param bool $import
     * 
     * @return void
     * 
     */
    protected function ImportDefaultStyles(bool $import = true):void {
        $this->_import_default_styles = $import;
    }

    /**
     * [Description for AddResource]
     *
     * @param string $type
     * @param string $resource
     * @param bool $isModule = false
     * [$isModule is only true if $type is script]
     * @return [type]
     * 
     */
    protected function AddResource(string $type, string $content, bool $isModule = false) {
        $Resource = new stdClass();

        $Resource->type = $type;
        $Resource->content = $content;
        $Resource->is_module = $isModule;

        $this->_resources[] = $Resource;
    }

    /**
     * [Create Html head schema]
     *
     * @return string
     * 
     */
    private function CreateHead():string {
        $head = '';
        $head .= sprintf("
            <!DOCTYPE html>
                <html lang='%s'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>{$this->_title}</title>
            
        ", Auth::Get('config', 'lang'));

        $v = date('His');

        if($this->_import_default_styles) {
            $head .= " <link rel='stylesheet' href='/css/backoffice/Generic.css?v={$v}'> ";
        }

        foreach($this->_resources as $resource) {
            switch(strtoupper($resource->type)) {
                case 'SCRIPT':
                    $head .= sprintf(" <script src='%s?{$v}' %s defer></script> ", $resource->content, $resource->is_module ? "type='module'" : '' );
                break;
                case 'STYLE':
                    $head .= sprintf(" <link rel='stylesheet' href='%s?v{$v}'> ", $resource->content);
                break;
                default:
                    // TODO Handle meta tags
                break;
            }
        }

        $head .= "
                </head>
            <body>
        ";

        return $head;
    }

    /**
     * [Create end HTML Schema]
     *
     * @return string
     * 
     */
    private function CreateEnd():string {
        return "
                </body>
            </html>
        ";
    }

    /**
     * [Creates the page]
     *
     * @return string
     * 
     */
    private function GetPage():string {
        $html = '';
        $html .= $this->CreateHead();
        $html .= $this->_result;
        $html .= $this->CreateEnd();
        return $html;
    }

    /**
     * [Returns the page]
     *
     * @return false|string
     * 
     */
    public function Request():false|string {
        if($this->_result === null) return false;
        return $this->_method === 'POST' ? json_encode($this->_result) : $this->GetPage();
    }
}