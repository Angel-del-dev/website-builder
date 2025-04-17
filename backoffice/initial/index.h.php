<?php

require_once(sprintf('%s/../components/Page/Backoffice.class.php', $_SERVER['DOCUMENT_ROOT']));
require_once(sprintf('%s/../lib/translations/Translation.class.php', $_SERVER['DOCUMENT_ROOT']));

class Page extends BackofficePage {
    public function __construct() {
        parent::__construct();
    }

    public function Get():void {
        if(!IS_INITIAL) {
            header(sprintf('Location: /%s/homes', BACKOFFICE_PREFIX));
            return;
        }
       
        // TODO Handle initial process
    }
}