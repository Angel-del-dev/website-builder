<?php 

require_once(sprintf('%s/../components/Page/Backoffice.class.php', $_SERVER['DOCUMENT_ROOT']));

class Page extends BackofficePage {
    public function __construct() {
        parent::__construct();
    }

    public function Get() {
        Auth::Destroy();
        header(sprintf('Location: /%s', BACKOFFICE_PREFIX));
    }
}