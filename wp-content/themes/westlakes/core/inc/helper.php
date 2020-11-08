<?php
if (! function_exists('config')) {
    function config($name)
    {
        $path = dirname(__DIR__ . '../src/config' . DIRECTORY_SEPARATOR).$name . '.php';
        $config = null;
        if (file_exists($path)){
            $config = include($path);
        }
        return $config;
    }
}
