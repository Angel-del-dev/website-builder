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
        $result = new stdClass();
        $this->_result = new stdClass();
        if(!Auth::IsLogged()) return;
        
        require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/Diagnostics.plugin/spider/Crawler.h.php");
        Crawler::Schedule('d', $params['Domain']);
        $this->_result = $result;
    }
}