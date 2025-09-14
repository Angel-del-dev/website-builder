<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/../models/Model.class.php");

class DomainsModel extends Model {
    protected static string $Table = 'DIAGNOSTICS_DOMAINS';
    protected static array $Keys = ['DOMAIN'];
    protected static array $Columns = ['VERIFICATOR', 'DOMAIN'];
}