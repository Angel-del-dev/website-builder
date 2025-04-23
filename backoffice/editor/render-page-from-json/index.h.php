<?php 

require_once(sprintf('%s/../components/Page/Backoffice.class.php', $_SERVER['DOCUMENT_ROOT']));
require_once(sprintf('%s/../lib/translations/Translation.class.php', $_SERVER['DOCUMENT_ROOT']));

class Page extends BackofficePage {
    public function __construct() {
        parent::__construct();
    }

    public function Get() {
        header(sprintf('Location: /%s/home', BACKOFFICE_PREFIX));
    }

    public function Post(array $params) {
        $this->_result = new stdClass();
        if(!Auth::IsLogged()) return;
        $Page = json_decode($params['Page']);
        require_once(sprintf('%s/../components/Editor.plugin/Editor.class.php', $_SERVER['DOCUMENT_ROOT']));

        $Editor = EditorCreator::Init($params);
        $Editor->Design();
        $Editor->SetPage($Page);
        $this->_result->Html = $Editor->Render();
        $TreeStructure = '';
        $Editor->DrawTreeStructure($TreeStructure, 'editor-panel-component-tree-structure', 100);
        $this->_result->TreeStructure = $TreeStructure;
    }
}