<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/../models/Model.class.php");

class PageModel extends Model {
    protected static string $Table = 'PAGES';
    protected static array $Keys = ['SLUG'];
    protected static array $Columns = ['SLUG'];
}