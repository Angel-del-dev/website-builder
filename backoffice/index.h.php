<?php

require_once(sprintf('%s/../components/Page/Backoffice.class.php', $_SERVER['DOCUMENT_ROOT']));
require_once(sprintf('%s/../lib/translations/Translation.class.php', $_SERVER['DOCUMENT_ROOT']));

class Page extends BackofficePage {
    public function __construct() {
        parent::__construct();
    }

    public function Get() {
        $this->AddTitle(Translation::Get('backoffice', 'login-title'));
        $this->AddResource('style', '/css/backoffice/Login.css');
        $this->AddResource('script', '/js/backoffice/Login.page.js', true);

        $d = $this->Renderer->StartDiv();
        $d->class = ' w-100 h-100 flex justify-center align-center ';

            $d = $this->Renderer->StartDiv();
            $d->class = ' radius-1 flex justify-center align-center p-4 ';
            $d->id = 'login';

                $f = $this->Renderer->StartForm();
                $f->id = 'login-form';
                $f->action = '/';
                $f->class .= ' flex justify-center align-center flex-column gap-2 ';

                    $this->Renderer->H1(Translation::Get('backoffice', 'backoffice-name'));
                    $text = $this->Renderer->Text('User');
                    $text->class .= ' text-center ';
                    $text->SetPlaceholder('Ej: test@gmail.com');

                    $pswd = $this->Renderer->Password('Password');
                    $pswd->class .= ' text-center ';
                    $pswd->SetPlaceholder('XXXXXXX');

                    $h1 = $this->Renderer->H1(Translation::Get('backoffice', 'invalid-credentials'));
                    $h1->id = 'error';
                    $h1->class = ' d-none ';
                    $h1->style = ' font-size: .9rem; color: var(--red); ';

                    $btn = $this->Renderer->Button('Submit', Translation::Get('backoffice', 'backoffice-login'));
                    $btn->class .= " btn-primary w-100 ";
                $this->Renderer->EndForm();

            $this->Renderer->EndDiv();

        $this->Renderer->EndDiv();
    }

    public function Post(array $params) {
        // TODO Handle post Login
        print_r($params);
        exit;
    }
}