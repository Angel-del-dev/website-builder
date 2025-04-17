<?php

require_once(sprintf('%s/../components/Page/Backoffice.class.php', $_SERVER['DOCUMENT_ROOT']));
require_once(sprintf('%s/../lib/translations/Translation.class.php', $_SERVER['DOCUMENT_ROOT']));

class Page extends BackofficePage {
    public function __construct() {
        parent::__construct();
    }

    private function LoadLanguagesIntoSelector(Select &$select):void {
        $Languages = Translation::GetAllTranslationsFromLocation('backoffice')['languages-list'];
        foreach($Languages as $k => $value) {
            $select->Add($k, $value);
        }
    }

    public function Get():void {
        if(!Auth::IsLogged()) {
            header(sprintf('Location: /%s', BACKOFFICE_PREFIX));
            return;
        }
        if(!IS_INITIAL) {
            header(sprintf('Location: /%s/home', BACKOFFICE_PREFIX));
            return;
        }

        $this->AddTitle(Translation::Get('backoffice', 'initial-process-title'));
        $this->AddResource('style', '/css/backoffice/Initial.css');
        $this->AddResource('script', '/js/backoffice/Initial.page.js', true);
       
        $d = $this->Renderer->StartDiv();
        $d->class = ' w-100 h-100 flex justify-center align-center ';

            $d = $this->Renderer->StartDiv();
            $d->id = 'initial-container';
            $d->class = ' h-fit flex justify-center align-center flex-column shadow-small radius-1 overflow-y p-4 gap-2 ';
            $d->style = ' background-color: var(--white); ';

                $select = $this->Renderer->Select('Language', Auth::Lang());
                $select->style = ' margin-left: auto; ';
                $this->LoadLanguagesIntoSelector($select);

                $separator = $this->Renderer->Separator();
                $separator->SetWidth('75%');

                $h1 = $this->Renderer->H1(Translation::Get('backoffice', 'initial-alert-process'));
                $h1->class .= ' text-justify ';
                $h1->style = ' color: var(--red); font-size: 1rem; ';

                $separator = $this->Renderer->Separator();
                $separator->SetWidth('75%');
                $this->Renderer->Label(Translation::Get('backoffice', 'initial-backoffice-prefix'), true);
                $t = $this->Renderer->Text('panel-prefix', sprintf('/%s', BACKOFFICE_PREFIX));
                $t->SetPlaceholder(Translation::Get('backoffice', 'initial-backoffice-prefix'));

            $this->Renderer->EndDiv();

        $this->Renderer->EndDiv();
    }
}