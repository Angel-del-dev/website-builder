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

        $this->StartGenericNav();

            $d = $this->Renderer->StartDiv();
            $d->class = ' flex justify-start align-center gap-2 ';
                $btn = $this->Renderer->Button('', "<i class='fa-solid fa-bars fa-1x'></i>");
                $btn->class = ' pointer toggle-main-menu ';
                $btn->style = 'background-color: transparent; color: var(--white); border: 0; outline: none;';

                $h1 = $this->Renderer->H1(Translation::Get('backoffice', 'menu-entries-events'));
                $h1->class = 'p-0 m-0';
                $h1->style = 'color: var(--white); font-size: 1.2rem;';
            $this->Renderer->EndDiv();
        $this->EndGenericNav();

        $d = $this->Renderer->StartDiv();
        $d->class = 'w-100 h-100 overflow-y flex justify-start align-start flex-column gap-2 p-2 no-user-select';

        $sql = $this->connection->newQuery("
            SELECT TRACE
            FROM DIAGNOSTICS_TRACE
            ORDER BY ID DESC
        ");
        $Traces = $sql->Execute();
        $sql->close();

        foreach($Traces as $trace) {
            $d = $this->Renderer->StartDiv();
            $d->class = ' flex w-100 p-2 radius-1 ';
            $d->style = ' background-color: var(--light); box-shadow: 2px 2px 2px 2px var(--lightgray); flex justify-between align-center';
                $this->Renderer->Raw($trace['TRACE']);
            $this->Renderer->EndDiv();
        }

        $this->Renderer->EndDiv();

    }

}