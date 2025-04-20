<?php

class Editor {
    private string $version;

    protected array $params;

    private bool $isDesign;

    private string $bg_aside;
    private string $panel_bg;

    private array $left_panel;
    private array $right_panel;

    public function __construct() {
        $this->params = [];
        $this->isDesign = false;

        $this->bg_aside = '#4f5e6b';
        $this->panel_bg = '#2d2f32';

        $this->left_panel = [];
        $this->right_panel = [];

        $this->_handle_version();
    }

    private function _handle_version():void {
        $this->version = '0.0.1';
    }

    public function Design(bool $isDesign = true):void {
        $this->isDesign = $isDesign;
    }

    public function SetParams(array $params):void {
        $this->params = $params;
    }

    // Start panel drawing
    private function _DrawGenericPanel(string $name):string {
        $panel = sprintf("
            <section 
                class='radius-1 w-100 flex-grow-1 flex justify-center align-center flex-column' 
                style='background-color: {$this->panel_bg};'
                data-panel='%s'
            >
        ", $name);

        $panel .= sprintf("
            <span
                class='w-100 flex justify-between align-center';
                style='padding: 0 0 5px 0;background-color: {$this->bg_aside};color: var(--white);'
            >
                %s
                <i class='removepanel pointer fa-solid fa-xmark fa-1x'></i>
            </span>
            <div
                class='p-1 flex-grow-1'
            >
            
            </div>
        ", Translation::Get('backoffice', $name));

        return $panel;
    }

    private function _DrawEndGenericPanel():string {
        return "
            </section>
        ";
    }
    private function DrawOptions(string &$html, string $name):void {
        $html .= $this->_DrawGenericPanel($name);
        $html .= $this->_DrawEndGenericPanel();
    }
    private function DrawReference(string &$html, string $name):void {
        $html .= $this->_DrawGenericPanel($name);
        $html .= $this->_DrawEndGenericPanel();
    }
    private function DrawTreeStructure(string &$html, string $name):void {
        $html .= $this->_DrawGenericPanel($name);
        $html .= $this->_DrawEndGenericPanel();
    }
    private function DrawComponentList(string &$html, string $name):void {
        $html .= $this->_DrawGenericPanel($name);
        $html .= $this->_DrawEndGenericPanel();
    }
    // End panel drawing

    private function GetPanelsConfiguration():void {
        if(!$this->isDesign) return;
        require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/Editor.plugin/models/EditorConfig.h.php");
        $configuration = EditorConfigModel::GetConfigurationFromUser();

        $functions_by_panel = [
            "editor-panel-component-options" => 'DrawOptions',
            "editor-panel-component-reference" => 'DrawReference',
            "editor-panel-component-tree-structure" => 'DrawTreeStructure',
            "editor-panel-component-list" => 'DrawComponentList',
        ];

        foreach($configuration as $panel) {
            if($panel['SIDE'] === 0) { // Left
                $this->left_panel[] = ['Panel' => $panel['PANEL'], 'Callback' => $functions_by_panel[$panel['PANEL']]];
            } else if($panel['SIDE'] === 1) { // Right
                $this->right_panel[] = ['Panel' => $panel['PANEL'], 'Callback' => $functions_by_panel[$panel['PANEL']]];
            }
        }
    }

    private function CreateLeftAside(string &$html):void {
        $html .= "
            <aside 
                class='overflow-y h-100 radius-1 flex justify-center align-center flex-column gap-2 p-2' 
                style='width: 200px; background-color: {$this->bg_aside};'
            >
        ";

        foreach($this->left_panel as $panel) {
            $callback = $panel['Callback'];
            $this->$callback($html, $panel['Panel']);
        }

        $html .= "</aside>";
    }

    private function CreateRightAside(string &$html):void {
        $html .= "
            <aside 
                class='overflow-y h-100 radius-1 flex justify-center align-center flex-column gap-2 p-2' 
                style='width: 200px; background-color: {$this->bg_aside};'
            >
        ";

        foreach($this->right_panel as $panel) {
            $callback = $panel['Callback'];
            $this->$callback($html, $panel['Panel']);
        }

        $html .= "</aside>";
    }

    private function CreateCanvas(string &$html):void {
        $html .= "
            <article class='overflow-y radius-1 h-100 flex-grow-1' style='background-color: #add8e6;'>
            </article>
        ";
    }

    public function Render():string {
        if($this->params == []) {
            Log::Entry('Editor $params cannot be empty');
            return sprintf("
                <div style='font-size: 1.2rem; font-weight: bold;' class='w-100 h-100 flex justify-center align-center'>
                    %s
                </div>
            ", Translation::Get('backoffice', 'editor-params-not-found'));
        }
        $html = '';
        $this->GetPanelsConfiguration();
        $html .= "<section class='p-2 w-100 h-100 flex justify-between align-start flex-grow-1 gap-2'>"; // Main container
            if($this->isDesign) $this->CreateLeftAside($html);
            $this->CreateCanvas($html);
            if($this->isDesign) $this->CreateRightAside($html);
        $html .= '</section>'; // Main container

        return $html;
    }
}

class EditorCreator {
    public static function Init(array $params):Editor {
        $Editor = new Editor();
        $Editor->SetParams($params);
        return $Editor;
   }
}