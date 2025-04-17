<?php

class Log {
    private static function CreateOrGetLog():string {
        $_base_path = sprintf("%s/../logs", $_SERVER['DOCUMENT_ROOT']);
        if(!is_dir($_base_path)) mkdir($_base_path);
        $_file_path = sprintf('%s/.log', $_base_path);
        if(!is_file($_file_path)) file_put_contents($_file_path, '');
        return $_file_path;
    }
    /**
     * [Adds a line to the log]
     *
     * @param string $message
     * 
     * @return void
     * 
     */
    public static function Entry(string $message):void {
        $log_path = self::CreateOrGetLog();
        $log = file_get_contents($log_path);
        $log .= sprintf("%s %s\n", date('d-m-Y H:i:s'), $message);
        file_put_contents($log_path, $log);
    }
}