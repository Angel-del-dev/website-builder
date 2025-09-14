<?php 

require_once(sprintf('%s/../components/Page/Backoffice.class.php', $_SERVER['DOCUMENT_ROOT']));
require_once(sprintf('%s/../lib/translations/Translation.class.php', $_SERVER['DOCUMENT_ROOT']));

require_once(sprintf('%s/../models/backoffice/Page.h.php', $_SERVER['DOCUMENT_ROOT']));

class Page extends BackofficePage {
    public function __construct() {
        parent::__construct();
    }

    public function Get() {
        header(sprintf('Location: /%s/home', BACKOFFICE_PREFIX));
    }

    public function Post(array $params) {
        $this->_result = new stdClass();
        if(!Auth::IsLogged()) {
            http_response_code(404);
            return;
        }
       
        PageModel::EditContents($params['Page'], $params['Contents']);

         require_once(sprintf('%s/../components/Editor.plugin/Editor.class.php', $_SERVER['DOCUMENT_ROOT']));

        $this->AddResource('style', '/css/backoffice/plugins/Editor.css');
        $this->AddResource('script', '/js/plugins/editor/editor.inc.js', true);

        $Compiled = EditorCreator::Init($params)->Compile();
    }
}