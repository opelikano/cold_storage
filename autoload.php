<?php

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/classes/';

    $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});