<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/../models/Model.class.php");

class EditorConfigModel extends Model {
    protected static string $Table = 'USEREDITORCONFIG';
    protected static array $Keys = ['USER'];
    protected static array $Columns = ['USER'];

    public static function GetConfigurationFromUser():array {
        self::Connection();

        $sql = self::$connection->newQuery('
            SELECT PANEL, SIDE, PANELORDER
            FROM USEREDITORPANELS
            WHERE USER = :USER
                AND VISIBLE = 1
            ORDER BY SIDE, PANELORDER ASC
        ');
        $sql->params->USER = Auth::Get('login', 'NAME');
        $Data = $sql->Execute();
        $sql->close();

        return $Data;
    }

    public static function RemoveUserPanel(string $panel):void {
        self::Connection();

        $sql = self::$connection->newQuery('
            DELETE FROM USEREDITORPANELS
            WHERE USER = :USER AND
                PANEL = :PANEL
        ');
        $sql->params->USER = Auth::Get('login', 'NAME');
        $sql->params->PANEL = $panel;
        $sql->Execute();
        $sql->close();
    }

    public static function GetAvailableFromUser():array {
        self::Connection();

        $sql = self::$connection->newQuery('
            SELECT EP.PANEL, EP.DESCRIPTION
            FROM EDITORPANELS EP
            WHERE NOT EXISTS(
                SELECT 1
                FROM USEREDITORPANELS
                WHERE USER = :USER AND
                PANEL = EP.PANEL
            )
        ');
        $sql->params->USER = Auth::Get('login', 'NAME');
        $data = $sql->Execute();
        $sql->close();

        return $data;
    }

    public static function InsertUserPanel(string $Panel, int $Side):void {
        self::Connection();

        // Check if the user has an edit config created and create it if not
        $sql = self::$connection->newQuery("
            SELECT 1
            FROM USEREDITORCONFIG
            WHERE USER = :USER
        ");
        $sql->params->USER = Auth::Get('login', 'NAME');
        $Data = $sql->Execute();
        $sql->close();
        if(count($Data) === 0) {
            $sql = self::$connection->newQuery("
                INSERT INTO USEREDITORCONFIG(USER) VALUES(:USER)
            ");
            $sql->params->USER = Auth::Get('login', 'NAME');
            $sql->Execute();
            $sql->close();
        }
        // Get order for the new panel
        $sql = self::$connection->newQuery("
            SELECT COALESCE(MAX(PANELORDER), 0) +1 AS NEWORDER
            FROM USEREDITORPANELS
            WHERE USER = :USER AND
                SIDE = :SIDE
        ");
        $sql->params->USER = Auth::Get('login', 'NAME');
        $sql->params->SIDE = $Side;        
        $Data = $sql->Execute();
        $sql->close();

        // Add panel
        $sql = self::$connection->newQuery("
            INSERT INTO USEREDITORPANELS
                (USER, PANEL, SIDE, PANELORDER)
            VALUES
                (:USER, :PANEL, :SIDE, :PANELORDER)
        ");
        $sql->params->USER = Auth::Get('login', 'NAME');
        $sql->params->PANEL = $Panel;
        $sql->params->SIDE = $Side;   
        $sql->params->PANELORDER = $Data[0]['NEWORDER'];            
        $sql->Execute();
        $sql->close();
    }

    public static function GetPageContents(string $Slug):array {
        self::Connection();

        $sql = self::$connection->newQuery('
            SELECT CONTENTS
            FROM PAGES
            WHERE SLUG = :SLUG
        ');
        $sql->params->SLUG = $Slug;
        $Data = $sql->Execute();
        $sql->close();

        if(count($Data) === 0) {
            $msg = "Could not obtain contents from '{$Slug}'";
            Log::Entry($msg);
            http_response_code(404);
            exit;
        }
        
        $Contents = trim($Data[0]['CONTENTS']);
        if($Contents === '') $Contents = '[]';

        return json_decode($Contents);
    }
}