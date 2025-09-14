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
        $this->AddResource('script', '/js/backoffice/Domains.page.js', true);

        $this->StartGenericNav();

            $d = $this->Renderer->StartDiv();
            $d->class = ' flex justify-start align-center gap-2 ';
                $btn = $this->Renderer->Button('', "<i class='fa-solid fa-bars fa-1x'></i>");
                $btn->class = ' pointer toggle-main-menu ';
                $btn->style = 'background-color: transparent; color: var(--white); border: 0; outline: none;';

                $h1 = $this->Renderer->H1(Translation::Get('backoffice', 'menu-entries-domains'));
                $h1->class = 'p-0 m-0';
                $h1->style = 'color: var(--white); font-size: 1.2rem;';
            $this->Renderer->EndDiv();

            $d = $this->Renderer->StartDiv();
            $d->class = ' flex justify-end align-center gap-2 ';
                $btn = $this->Renderer->Button('', "<i class='fa-solid fa-plus fa-1x'></i>");
                $btn->class = ' pointer add-domain ';
                $btn->style = 'background-color: transparent; color: var(--white); border: 0; outline: none;';
            $this->Renderer->EndDiv();

        $this->EndGenericNav();

        $sql = $this->connection->newQuery("
            SELECT DOMAIN, VALIDATED_AT
            FROM DIAGNOSTICS_DOMAINS
            ORDER BY CREATED_AT DESC
        ");
        $Domains = $sql->Execute();
        $sql->close();

        $d = $this->Renderer->StartDiv();
        $d->class = 'w-100 h-100 overflow-y flex justify-start align-start flex-column gap-2 p-2 no-user-select';
        
            $element_class = 'flex align-center flex-grow-1';

            foreach($Domains as $domain) {
                $d = $this->Renderer->StartDiv();
                $d->AddAttribute('domain', $domain['DOMAIN']);
                $d->class = ' flex w-100 p-2 radius-1 ';
                $d->style = ' background-color: var(--light); box-shadow: 2px 2px 2px 2px var(--lightgray); flex justify-between align-center';
                    $d = $this->Renderer->StartDiv();
                    $d->class = "{$element_class} justify-start";
                        $h2 = $this->Renderer->H2($domain['DOMAIN']); 
                        $h2->class = 'p-0 m-0';
                        $h2->style = 'font-weight: normal; font-size: 1rem;';
                    $this->Renderer->EndDiv();

                    $d = $this->Renderer->StartDiv();
                    $d->class = "{$element_class} justify-end gap-2";
                        if(is_null($domain['VALIDATED_AT'])) {
                            $link = $this->Renderer->Link('', '');
                            $link->class = ' validate_domain pointer ';
                            $link->SetIcon('check', '1x');
                            $link->style = 'color: var(--red); text-decoration: none;';
                        } else {
                            $link = $this->Renderer->Link('', '');
                            $link->SetIcon('check', '1x');
                            $link->style = 'color: var(--green); text-decoration: none;';
                        }
                        $link = $this->Renderer->Link('', '#');
                        $link->class = 'delete';
                        $link->SetIcon('trash-alt', '1x');
                        $link->style = 'color: var(--red); text-decoration: none;';
                    $this->Renderer->EndDiv();
                $this->Renderer->EndDiv();
            }
        $this->Renderer->EndDiv();

    }

}