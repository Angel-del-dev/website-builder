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
}