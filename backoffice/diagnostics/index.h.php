<?php 

require_once(sprintf('%s/../components/Page/Backoffice.class.php', $_SERVER['DOCUMENT_ROOT']));
require_once(sprintf('%s/../lib/translations/Translation.class.php', $_SERVER['DOCUMENT_ROOT']));

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
        $this->AddResource('script', '/js/backoffice/Diagnostics.page.js', true);

        $this->StartGenericNav();

            $d = $this->Renderer->StartDiv();
            $d->class = ' flex justify-start align-center gap-2 ';
                $btn = $this->Renderer->Button('', "<i class='fa-solid fa-bars fa-1x'></i>");
                $btn->class = ' pointer toggle-main-menu ';
                $btn->style = 'background-color: transparent; color: var(--white); border: 0; outline: none;';

                $h1 = $this->Renderer->H1(Translation::Get('backoffice', 'menu-section-diagnostics'));
                $h1->class = 'p-0 m-0';
                $h1->style = 'color: var(--white); font-size: 1.2rem;';
            $this->Renderer->EndDiv();

            $d = $this->Renderer->StartDiv();
            $d->class = ' flex justify-end align-center gap-2 ';
                $btn = $this->Renderer->Button('', "<i class='fa-solid fa-plus fa-1x'></i>");
                $btn->class = ' pointer begin-diagnostics ';
                $btn->style = 'background-color: transparent; color: var(--white); border: 0; outline: none;';
            $this->Renderer->EndDiv();

        $this->EndGenericNav();

    }

}