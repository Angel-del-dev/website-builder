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
        if(!IS_INITIAL) return;
        $script = file_get_contents(sprintf('%s/../schema/initial.script.sql', $_SERVER['DOCUMENT_ROOT']));

        if(trim($script) === '') return;

        $sql = $this->connection->newQuery($script);
        $sql->Execute();
        $sql->close();
    }
}