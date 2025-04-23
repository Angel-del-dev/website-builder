<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/../models/Model.class.php");

class PageModel extends Model {
    protected static string $Table = 'PAGES';
    protected static array $Keys = ['SLUG'];
    protected static array $Columns = ['SLUG'];

    public static function EditContents(string $Slug, string $Contents):void {
        self::Connection();
        $sql = self::$connection->newQuery("
            UPDATE PAGES
                SET CONTENTS = :CONTENTS
            WHERE SLUG = :SLUG
        ");
        $sql->params->SLUG = $Slug;
        $sql->params->CONTENTS = $Contents;
        $sql->Execute();
        $sql->close();
    }
}