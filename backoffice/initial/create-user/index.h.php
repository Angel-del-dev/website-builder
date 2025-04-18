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
            INSERT INTO USERS
                (NAME, PASSWORD, ROLE)
            VALUES
                (:NAME, :PASSWORD, 'SuperAdmin')
        ");
        $sql->params->NAME = $params['User'];
        $sql->params->PASSWORD = md5($params['Password']);
        $sql->Execute();
        $sql->close();
    }
}