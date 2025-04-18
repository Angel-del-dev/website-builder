<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/BackofficeRenderer/Renderer.class.php");

require_once(sprintf('%s/../components/pdo/Mysql.class.php', $_SERVER['DOCUMENT_ROOT']));

class BackofficePage {
    private string $_method;
    private array $_resources;
    private bool $_import_default_styles;
    private string $_title;

    protected Renderer $Renderer;

    protected MysqlPdo $connection;

    private bool $isLayoutInvoked;

    protected string|stdClass|array|bool $_result;
    public function __construct() {
        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_result = false;
        $this->_resources = [];
        $this->_import_default_styles = true;
        $this->_title = '';
        $this->isLayoutInvoked = false;
        $this->SetRenderer();
        $this->SetupConnection();
    }

    private function SetupConnection():void {
        $cfg = Parse::CFG()->database;
        
        $this->connection = new MysqlPdo(
            $cfg->host,
            $cfg->name,
            $cfg->user,
            $cfg->password,
            $cfg->port
        );
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
        $panel_prefix = BACKOFFICE_PREFIX;
        $head .= sprintf("
            <!DOCTYPE html>
                <html lang='%s'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>{$this->_title}</title>
                    <script>
                        const BACKOFFICE_PREFIX = `{$panel_prefix}`;
                    </script>
                    <script src='/js/components/global.inc.js'></script>
            
        ", Auth::Get('config', 'lang'));

        $v = date('His');

        if($this->_import_default_styles) {
            $head .= "
                <link rel='stylesheet' href='/css/backoffice/Variables.css?v={$v}'>
                <link rel='stylesheet' href='/css/Generic.css?v={$v}'>
                <link rel='stylesheet' href='/css/backoffice/Generic.css?v={$v}'>
            ";
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

    private function SetRenderer():void {
        $this->Renderer = new Renderer();
        $div = $this->Renderer->StartDiv(); // Initial wrapper
        $div->id = 'root';
        $div->class = ' w-100 h-100 ';
    }

    /**
     * [Creates the page]
     *
     * @return string
     * 
     */
    private function GetPage():string {
        $this->Renderer->EndDiv(); // Initial wrapper

        $html = '';
        $html .= $this->CreateHead();
        $html .= $this->Renderer->Render();
        if($this->isLayoutInvoked) $this->EndGenericLayout();
        $html .= $this->CreateEnd();
        return $html;
    }

    /**
     * [Function in charge of creating the backoffice main layout and styles]
     *
     * @return void
     * 
     */
    protected function GenericLayout():void {
        $this->isLayoutInvoked = true;

        $d = $this->Renderer->StartSection(); // Main container
        $d->class = ' w-100 h-100 flex justify-center align-center ';

            $d = $this->Renderer->StartAside(); // Menu
            $d->id = 'main-menu';
            $d->class = ' h-100 ';
            $d->style = ' width: 200px; background-color: teal; ';
                require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/BackofficeRenderer/MainMenu.class.php");
                $menu = new MainMenu($this->connection);
                $this->Renderer->Raw($menu->Render());

            $this->Renderer->EndAside();

            $d = $this->Renderer->StartArticle(); // Page content
            $d->class = ' h-100 overflow-y flex-grow-5 ';
            $d->id = 'main-aside';
            $d->style = ' background-color: pink; ';
    }

    private function EndGenericLayout():void {
            $this->Renderer->EndArticle(); // Page content
        $this->Renderer->EndSection(); // Main container
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