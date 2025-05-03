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

        $req = new Media();
        $req->Get();
        $req->Accept('image/png');
        $req->EndPoint('/file2/23');
        $req->Execute();
        $res = $req->Response();

        print_r($res->StatusCode());
        exit;
    }
}