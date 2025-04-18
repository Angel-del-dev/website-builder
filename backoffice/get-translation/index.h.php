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

        $lang = trim($params['Specific_lang']);
        if($lang === '') {
            $lang = Auth::Get('config', 'lang');
        }
        
        
        $result->Translations = Translation::GetAllTranslationsFromLocation($params['Location']);
        $result->Lang = $lang;
        

        $this->_result = $result;
    }
}