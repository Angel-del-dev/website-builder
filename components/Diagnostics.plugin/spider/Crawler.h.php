<?php 
class Crawler {
    private static function get_command_linux(string $route, string $key, string $value) {
        return sprintf('nohup %s -%s %s > /dev/null 2>&1 &', $route, $key, $value);
    }
    private static function get_command_windows(string $route, string $key, string $value) {
        // return sprintf('md /c start "" /B "%s.exe" -%s %s', $route, $key, $value);
        return $route.".exe -{$key} {$value}";
    }
    public static function Schedule(string $key, string $value) {
        $route = __DIR__.'/spider';
        $cmd = '';
        if (PHP_OS_FAMILY === "Windows") {
            $cmd = self::get_command_windows($route, $key, $value);
        } elseif (PHP_OS_FAMILY === "Linux") {
            $cmd = self::get_command_linux($route, $key, $value);
        }
        if($cmd === '') return;
        shell_exec($cmd);
    }
}