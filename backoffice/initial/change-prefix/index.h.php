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
        $sql = $this->connection->newQuery("
            INSERT INTO CONFIGURATION 
                (NAME, VALUE) 
            VALUES 
                ('BACKOFFICE_PREFIX', :PREFIX)
        ");
        $sql->params->PREFIX = $params['Prefix'];
        $sql->Execute();
        $sql->close();
    }
}