<?php 

require_once(sprintf('%s/../components/Page/Backoffice.class.php', $_SERVER['DOCUMENT_ROOT']));

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

        require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/Editor.plugin/models/EditorConfig.h.php");
        EditorConfigModel::RemoveUserPanel($params['Panel']);
    }
}