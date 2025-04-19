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
       
        if(PageModel::Exists([['SLUG', '=', strtolower($params['Slug'])]])) {
            $this->_result->message = Translation::Get('backoffice', 'backoffice-exists');
            return;
           
        }
        
        // TODO Check if requested SLUG is a reserved route(It comes from a plugin)        
        PageModel::Insert(['SLUG' => strtolower($params['Slug'])]);
    }
}