<?php

require_once(sprintf('%s/../components/Page/Backoffice.class.php', $_SERVER['DOCUMENT_ROOT']));
require_once(sprintf('%s/../lib/translations/Translation.class.php', $_SERVER['DOCUMENT_ROOT']));

class Page extends BackofficePage {
    
    public function __construct() {
        parent::__construct();
    }

    public function Get() {
        if(Auth::IsLogged()) {
            Header(sprintf('Location: /%s/home', BACKOFFICE_PREFIX));
            return;
        }
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

                    $h1 = $this->Renderer->H1('');
                    $h1->id = 'error';
                    $h1->class = ' d-none m-0 p-0 ';
                    $h1->style = ' font-size: .9rem; color: var(--red); ';

                    $btn = $this->Renderer->Button('Submit', Translation::Get('backoffice', 'backoffice-login'));
                    $btn->class .= " btn-primary w-100 ";
                $this->Renderer->EndForm();

            $this->Renderer->EndDiv();

        $this->Renderer->EndDiv();
    }

    public function Post(array $params):void {
        $this->_result['message'] = '';
        $sql = $this->connection->newQuery("
            SELECT NAME, EMAIL, ROLE, BLOCKED
            FROM USERS
            WHERE UPPER(NAME) = :NAME AND
                PASSWORD = :PASSWORD
        ");
        $sql->params->NAME = strtoupper($params['User']);
        $sql->params->PASSWORD = md5($params['Password']);
        $Data = $sql->Execute();
        $sql->close();
        
        if(count($Data) === 0) {
            $this->_result['message'] = Translation::Get('backoffice', 'invalid-credentials');
            return;
        }

        if($Data[0]['BLOCKED'] === 1) {
            $this->_result['message'] = Translation::Get('backoffice', 'account-blocked');
            return;
        }

        Auth::Set('login', 'NAME', $Data[0]['NAME']);
        Auth::Set('login', 'EMAIL', $Data[0]['EMAIL']);
        Auth::Set('login', 'ROLE', $Data[0]['ROLE']);
    }
}