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
            $d->class = ' no-user-select h-fit flex justify-center align-start flex-column shadow-small radius-1 overflow-y p-4 gap-2 ';
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
                $t = $this->Renderer->Text('panel-prefix', sprintf('%s', BACKOFFICE_PREFIX));
                $t->SetPlaceholder(Translation::Get('backoffice', 'initial-backoffice-prefix'));
                $t->class .= ' w-50 ';

                $separator = $this->Renderer->Separator();
                $separator->SetWidth('75%');

                $this->Renderer->Label(Translation::Get('backoffice', 'backoffice-user'), true);
                $t = $this->Renderer->Text('AdminUser', '');
                $t->SetPlaceholder(Translation::Get('backoffice', 'backoffice-user'));
                $t->class .= ' w-50 ';

                $separator = $this->Renderer->Separator();
                $separator->SetWidth('75%');

                $d = $this->Renderer->StartDiv();
                $d->class = ' w-100 flex justify-start align-start gap-3 ';
                    $this->Renderer->Label(Translation::Get('backoffice', 'backoffice-password'), true);
                    $this->Renderer->Label(Translation::Get('backoffice', 'backoffice-confirm-password'), true);
                $this->Renderer->EndDiv();
                $d = $this->Renderer->StartDiv();
                $d->class = ' w-100 flex justify-start align-start gap-3 ';

                    $t = $this->Renderer->Password('AdminPassword', '');
                    $t->SetPlaceholder(Translation::Get('backoffice', 'backoffice-password'));
                    $t->class .= ' w-50 ';

                    $t = $this->Renderer->Password('ConfirmPassword', '');
                    $t->SetPlaceholder(Translation::Get('backoffice', 'backoffice-confirm-password'));
                    $t->class .= ' w-50 ';

                $this->Renderer->EndDiv();

                $separator = $this->Renderer->Separator();
                $separator->SetWidth('75%');

                $btn = $this->Renderer->Button('Confirm', Translation::Get('backoffice', 'backoffice-confirm'));
                $btn->class = ' btn-success ';
                $btn->style  = ' margin-left: auto; ';

                $d = $this->Renderer->StartDiv();
                $d->id = 'progress';
                $d->class = ' w-100 p-2 d-none flex justify-start align-start flex-column overflow-y ';
                $d->style = ' background-color: white; height: 150px; ';
                $this->Renderer->EndDiv();
            $this->Renderer->EndDiv();

        $this->Renderer->EndDiv();
    }
}