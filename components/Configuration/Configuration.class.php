<?php

require_once(sprintf('%s/../components/pdo/Mysql.class.php', $_SERVER['DOCUMENT_ROOT']));

class Configuration {
    private static function _connection():MysqlPdo {
        $cfg = Parse::CFG()->database;
        
        return new MysqlPdo(
            $cfg->host,
            $cfg->name,
            $cfg->user,
            $cfg->password,
            $cfg->port
        );
    }
    public static function Get(string $key):string {
        $result = '';
        $sql = self::_connection()->newQuery("
            SELECT VALUE
            FROM CONFIGURATION
            WHERE NAME = :NAME
        ");
        $sql->params->NAME = $key;
        $Data = $sql->Execute();
        $sql->close();

        if(count($Data) > 0) {
            $result = $Data[0]['VALUE'];
        }

        return $result;
    }
}