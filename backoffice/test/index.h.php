<?php 

require_once(sprintf('%s/../components/Page/Backoffice.class.php', $_SERVER['DOCUMENT_ROOT']));
require_once(sprintf('%s/../lib/translations/Translation.class.php', $_SERVER['DOCUMENT_ROOT']));
require_once(sprintf('%s/../components/Request/Media.h.php', $_SERVER['DOCUMENT_ROOT']));
class Page extends BackofficePage {
    public function __construct() {
        parent::__construct();
    }

    public function Get() {
        if(!Auth::IsLogged()) {
            header(sprintf('Location: /%s', BACKOFFICE_PREFIX));
            return;
        }
        if(IS_INITIAL) {
            header(sprintf('Location: /%s/initial', BACKOFFICE_PREFIX));
            return;
        }

        $this->GenericLayout();

        $this->StartGenericNav();
        $this->EndGenericNav();
        $h1 = $this->Renderer->H1('Testing page');
        $h1->class = ' w-100 flex-grow-1 flex justify-center align-center m-0 p-0 ';
        print_r('<pre>');
        $req = new Media();
        $req->Debug();
        $req->Authenticate('username', 'password');
        $req->Delete();
        $req->EndPoint('/file/unlink/bXxQ3gZYkpY1lVlTXIFhjsoWqZUGWHVheUJvnd26LeVs69U0o6ZO8t6rOPFdeLHCm0JpOQxI8j5iH4mtkfkRGky1O9IUgkCE5V9Z');
        
        $req->Execute();
        $res = $req->Response();

        print_r($res->StatusCode());
        print_r($res->RawResponse());
        exit;
    }
}