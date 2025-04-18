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

        $sql = $this->connection->newQuery("
            UPDATE USERS
                SET BLOCKED = 1
            WHERE NAME = :NAME
        ");
        $sql->params->NAME = 'Initial';
        $sql->Execute();
        $sql->close();
    }
}