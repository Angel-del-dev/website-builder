<?php

class Editor {
    private string $version;

    protected array $params;

    private bool $isDesign;

    private string $bg_aside;
    private string $panel_bg;

    private array $left_panel;
    private array $right_panel;

    private array $component_tree;
    private array $PageStructure;

    public function __construct() {
        $this->params = [];
        $this->isDesign = false;

        $this->bg_aside = '#4f5e6b';
        $this->panel_bg = '#2d2f32';

        $this->left_panel = [];
        $this->right_panel = [];
        $this->component_tree = [];
        $this->PageStructure = [];

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
    private function _DrawGenericPanel(string $name, float $maxHeight = 100):string {
        $panel = sprintf("
            <section 
                class='radius-1 w-100 flex-grow-1 flex justify-center align-center flex-column' 
                style='background-color: {$this->panel_bg}; flex: 1; max-height: %s;'
                data-panel='%s'
            >
        ","{$maxHeight}%", $name);

        $panel .= sprintf("
            <span
                class='w-100 flex justify-between align-center';
                style='padding: 0 0 5px 0;background-color: {$this->bg_aside};color: var(--white);'
            >
                %s
                <i class='removepanel pointer fa-solid fa-xmark fa-1x'></i>
            </span>
            <div
                class='p-1 flex-grow-1 w-100 h-100 overflow-y overflow-x flex justify-start align-start flex-column gap-1'
            >
        ", Translation::Get('backoffice', $name));

        return $panel;
    }

    private function _DrawEndGenericPanel():string {
        return "
                </div>
            </section>
        ";
    }
    private function DrawOptions(string &$html, string $name, float $maxHeight = 100):void {
        $html .= $this->_DrawGenericPanel($name, $maxHeight);
        $html .= $this->_DrawEndGenericPanel();
    }
    private function DrawReference(string &$html, string $name, float $maxHeight = 100):void {
        $html .= $this->_DrawGenericPanel($name, $maxHeight);
        $html .= $this->_DrawEndGenericPanel();
    }

    private function _draw_tree_slice(string &$html, array $children):void {
        if($children === []) return;

        $html .= "<ul class='w-100 flex justify-start align-start flex-column gap-1' style='padding-left: 20px;list-style: none;'>";

        foreach($children as $child) {
            $html .= "<li class='component-tree-item flex justify-start align-start flex-column gap-1' style='white-space:nowrap; width: fit-content;'>";
            $type = Translation::Get('backoffice', "editor-plugin-{$child['type']}");
                $html .= "
                    <span id='tree-{$child['name']}' title='{$child['name']} : {$type}' class='p-1' style='min-width: 100%; width: fit-content !important;'>
                        {$child['name']} : {$type}
                    </span>
                ";
                if(isset($child['children'])) {
                    $this->_draw_tree_slice($html, $child['children']);
                }
            $html .= "</li>";
        }

        $html .= "</ul>";
    }

    public function DrawTreeStructure(string &$html, string $name, float $maxHeight = 100):void {
        $html .= $this->_DrawGenericPanel($name, $maxHeight);

        $html .= "
            <ul class='p-0 w-100 h-100 m-0 flex justify-start align-start flex-column gap-1' style='list-style: none; color: var(--white);'>
                <li class='pointer w-100 component-tree-item'><span class='w-100 p-1'>Root : Window</span></li>
        ";
        $this->_draw_tree_slice($html, $this->component_tree['root']['children']);
        $html .= "
            </ul>
        ";

        $html .= $this->_DrawEndGenericPanel();
    }
    private function DrawComponentList(string &$html, string $name, float $maxHeight = 100):void {
        $html .= $this->_DrawGenericPanel($name, $maxHeight);
        $components = scandir(sprintf('%s/../components/Editor.plugin/components', $_SERVER['DOCUMENT_ROOT']));
        $html .= '<ul class="p-0 m-0" style="list-style: none;">';
        foreach($components as $component) {
            if(in_array($component, ['.', '..'])) continue;
            $name = Translation::Get('backoffice', "editor-plugin-{$component}");
            $html .= "
                <li title='{$name}' data-type='component-schema' class='text-ellipsis p-1 component-list-item' style='color: var(--white);'>{$name}</li>
            ";
        }
        $html .= '</ul>';
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

    private function _GetPercentageFilled(array $section):float {
        $count = count($section);
        if($count === 0) return 100;
        return 100 / $count;
    }

    private function CreateLeftAside(string &$html):void {
        if(count($this->left_panel) === 0) return;
        $html .= "
            <aside 
                class='no-user-select overflow-y h-100 radius-1 flex justify-center align-center flex-column gap-2 p-2' 
                style='width: 200px; background-color: {$this->bg_aside};'
            >
        ";
        $maxHeight = $this->_GetPercentageFilled($this->left_panel);
        foreach($this->left_panel as $panel) {
            $callback = $panel['Callback'];
            $this->$callback($html, $panel['Panel'], $maxHeight);
        }

        $html .= "</aside>";
    }

    private function CreateRightAside(string &$html):void {
        if(count($this->right_panel) === 0) return;
        $html .= "
            <aside 
                class='no-user-select overflow-y h-100 radius-1 flex justify-center align-center flex-column gap-2 p-2' 
                style='width: 200px; background-color: {$this->bg_aside};'
            >
        ";
        $maxHeight = $this->_GetPercentageFilled($this->right_panel);
        
        foreach($this->right_panel as $panel) {
            $callback = $panel['Callback'];
            $this->$callback($html, $panel['Panel'], $maxHeight);
        }

        $html .= "</aside>";
    }

    private function CreateCanvas(string &$html):void {
        $class = $this->isDesign ? ' builder-canvas ' : '';
        $html .= "
            <article 
                class='overflow-y radius-1 h-100 flex-grow-1 {$class}' 
                style='background-color: #add8e6;'
            >
        ";

        $Page = $this->params;

        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/Editor.plugin/models/EditorConfig.h.php");
            $Page = EditorConfigModel::GetPageContents($this->params['p']);
            $tree = [
                'root' => [
                    'children' => []
                ]
            ];
            $this->PageStructure = $Page;
        }
        $tree['root']['children'] = $this->ParsePage($Page, $html, []);
        
        if($this->isDesign) $this->component_tree = $tree;

        $html .= "</article>";
    }

    private function ParsePage(array $Elements, string &$html, array $tree_slice = []):array {
        $base_route = "{$_SERVER['DOCUMENT_ROOT']}/../components/Editor.plugin/components";
        $i = 0;
        foreach($Elements as $element) {
            if(!is_dir("{$base_route}/{$element->type}")) continue;
            if(!is_file("{$base_route}/{$element->type}/component.class.php")) continue;
            require_once("{$base_route}/{$element->type}/component.class.php");
            
            $component = new $element->type($element, $this->isDesign);

            $html .= $component->StartRender($i++);

            $tree_element = [ 'name' => $element->name, 'type' => $element->type, 'children' => [] ];

            if(isset($element->children) && $element->children !== []) {
                $tree_element['children'] = $this->ParsePage($element->children, $html, []);
            }
            
            $html .= $component->EndRender();

            $tree_slice[] = $tree_element;
            
        }

        return $tree_slice;
    }

    public function SetPage(array $Page):void {
        $this->params = $Page;
    }

    public function GetControls(array $Properties):array {
        $base_route = "{$_SERVER['DOCUMENT_ROOT']}/../components/Editor.plugin/components";
        if(!is_dir("{$base_route}/{$Properties['type']}")) return [];
        if(!is_file("{$base_route}/{$Properties['type']}/component.class.php")) return [];
        require_once("{$base_route}/{$Properties['type']}/component.class.php");
        $component = new $Properties['type'](json_decode(json_encode($Properties), false), true);
        return $component->GetControls();
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

        $canvas_html = '';
        $this->CreateCanvas($canvas_html);
        $id = $this->isDesign ? "id='main-editor'" : '';
        $html .= "<section {$id} class='p-2 w-100 flex justify-between align-start flex-grow-1 gap-2' style='height: 90svh;'>"; // Main container
            if($this->isDesign && $_SERVER['REQUEST_METHOD'] === 'GET') $this->CreateLeftAside($html);
            $html .= $canvas_html;
            if($this->isDesign && $_SERVER['REQUEST_METHOD'] === 'GET') $this->CreateRightAside($html);
        $html .= '</section>'; // Main container

        if($this->isDesign && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $html .= sprintf("
                <script> 
                    const PAGESTRUCTURE = JSON.parse('%s');
                    const CURRENTPAGE = `%s`;
                </script>
            ", json_encode($this->PageStructure), $this->params['p']);
        }

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