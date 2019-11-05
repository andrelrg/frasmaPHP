<?php

class Settings
{
    public static function getSettings(string $filePath) {
        global $SETTINGS;
        $str  = file_get_contents($filePath);
        $SETTINGS = json_decode($str, true);
    }
}