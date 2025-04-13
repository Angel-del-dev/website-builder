<?php

class Parse {
    private static function _GetFile(string $route):string {
        return file_get_contents($route);
    }
    /**
     * [Parse a CFG file to a stdClass]
     *
     * @return stdClass
     * 
     */
    public static function CFG():stdClass {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/../lib/functions.inc.php");
        $cfg = explode("\n", self::_GetFile(GetRootPath().'initial.cfg'));

        $obj = new stdClass();
        $current_section = null;
        foreach($cfg as $line) {
            $line = trim($line);
            if(strlen($line) === 0) continue;
            if($line[0] === '[') {
                // Start of a section
                $current_section = substr($line, 1, strlen($line) - 2);
                $obj->$current_section = new stdClass();
                continue;
            }

            if(!str_contains($line, '=')) continue;

            $key_value = explode('=', $line);
            $key = trim($key_value[0]);
            $value = trim($key_value[1]);
            if(!is_null($current_section)) {
                $obj->$current_section->$key = $value;
            } else {
                $obj->$key = $value;
            }
        }
        return $obj;
    }
}