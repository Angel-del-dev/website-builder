<?php

class Auth {
    /**
     * [Description for Start]
     *
     * @return void
     * 
     */
    public static function Start():void {
        session_start();
    }
    /**
     * [Description for Destroy]
     *
     * @return void
     * 
     */
    public static function Destroy():void {
        $_SESSION['login'] = [];
    }
    /**
     * [Description for Get]
     *
     * @param string $section
     * @param string $key
     * 
     * @return null|int|string|array|stdClass
     * 
     */
    public static function Get(string $section, string $key):null|int|string|array|stdClass {
        if(!isset($_SESSION[$section][$key])) return null;
        return $_SESSION[$section][$key];
    }
    /**
     * [Description for Set]
     *
     * @param string $section
     * @param string $key
     * @param mixed $value
     * 
     * @return void
     * 
     */
    public static function Set(string $section, string $key, $value):void {
        if(!isset($_SECTION[$section][$key])) return;
        $_SESSION[$section][$key] = $value;
    }

    /**
     * [Description for GetAll]
     *
     * @return array
     * 
     */
    public static function GetAll():array {
        return $_SESSION;
    }

    /**
     * [Description for Setup]
     *
     * @return void
     * 
     */
    public static function Setup():void {
        if(isset($_SESSION['login'])) return;
        $_SESSION['login'] = [];
        $_SESSION['config'] = [
            'lang' => 'en'
        ];
    }

    public static function IsLogged():bool {
        return self::Get('login', 'name') !== '';
    }
}