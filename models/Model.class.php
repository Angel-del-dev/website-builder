<?php

require_once("{$_SERVER['DOCUMENT_ROOT']}/../components/pdo/Mysql.class.php");

define('_AND', ' AND ');
define('_OR', ' OR ');

class Model {
    protected static MysqlPdo $connection;
    protected static string $Table = '';
    protected static array $Keys = [];
    protected static array $Columns = [];
    protected static function Connection():void {
        $cfg = Parse::CFG()->database;
        
        self::$connection = new MysqlPdo(
            $cfg->host,
            $cfg->name,
            $cfg->user,
            $cfg->password,
            $cfg->port
        );
    }


    /**
     * [Get records]
     *
     * @param array $Params = [ [KEY, SIGN, VALUE, TYPECONCAT] ]
     * {{$TYPECONCAT = [ _AND, _OR ]}}
     * 
     * @return array]
     * 
     */
    public static function Get($Params = []):array {
        $query = sprintf(" SELECT %s FROM %s ", implode(', ', static::$Columns), static::$Table);
        foreach($Params as $param) {
            $typeconcat = isset($param[3]) ? $param[3] : '';
            $query .= sprintf(' WHERE %s %s :%s %s ', $param[0], $param[1], $param[0], $typeconcat);
        }

       self::Connection();
       
       $sql = self::$connection->newQuery($query);
        foreach($Params as $Param) {
            $Key = $Param[0];
            $sql->params->$Key = $Param[2];
        }
        $Data = $sql->Execute();
        $sql->close();

        return $Data;
    }

    /**
     * [Check if records exist from a specific filter]
     *
     * @param array $Params = [ [KEY, SIGN, VALUE, TYPECONCAT] ]
     * {{$TYPECONCAT = ['AND', 'OR']}}
     * 
     * @return bool
     * 
     */
    public static function Exists($Params = []):bool {
        return count(self::Get($Params)) > 0;
    }

    /**
     * [Create a new record]
     *
     * @param array $data
     * {{ $data = [key => value] }}
     * 
     * @return void
     * 
     */
    public static function Insert(array $data):void {
        self::Connection();
        $Keys = [];
        $Values = [];

        foreach($data as $k => $_) {
            $Keys[] = $k;
            $Values[] = ":{$k}";
        }
        
        $query = sprintf("
            INSERT INTO %s (%s) VALUES (%s)
        ", static::$Table, implode(', ', $Keys), implode(', ', $Values));

        $sql = self::$connection->newQuery($query);
        foreach($data as $k => $value) {
            $sql->params->$k = $value;
        }

        $sql->Execute();
        $sql->close();
    }


    /**
     * [Delete function]
     *
     * @param array($key => $value) ...$DeleteParams
     * 
     * @return [type]
     * 
     */
    public static function Delete(...$DeleteParams) {
        self::Connection();

        foreach($DeleteParams as $param) {
            $query = sprintf(" DELETE FROM %s WHERE ", static::$Table);
            $conditions = [];

            foreach(static::$Keys as $key) {
                $conditions[] = sprintf(" %s = :%s ", $key, $key);
            }
            $query .= implode(' AND ', $conditions);
            $sql = self::$connection->newQuery($query);
            foreach($param as $k => $value) {
                $sql->params->$k = $value;
            }
            
            $sql->Execute();
            $sql->close();
        }
        
    }
}