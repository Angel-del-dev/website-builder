<?php

class Translation {
    /**
     * [Getting the translation object from a location]
     *
     * @param string $location
     * 
     * @return array
     * 
     */
    public static function GetAllTranslationsFromLocation(string $location):array {
        $file = file_get_contents(sprintf("%s/../translations/%s.json", $_SERVER['DOCUMENT_ROOT'], $location));
        // TODO Add to log
        return json_decode($file, true);
    }

    public static function Get(string $location, string $key):string {
        $translations = self::GetAllTranslationsFromLocation($location);
        if(!isset($translations[$key])) Log::Entry(sprintf('Translation not found "%s"', $key));
        if(!isset($translations[$key][Auth::Get('config', 'lang')])) Log::Entry(sprintf('Translation not found "%s" for language "%s"', $key, Auth::Get('config', 'lang')));
        if(!isset($translations[$key]) || !isset($translations[$key][Auth::Get('config', 'lang')])) return $translations["translation-not-found"][Auth::Get('config', 'lang')];

        return $translations[$key][Auth::Get('config', 'lang')];        
    }
}