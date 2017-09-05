<?php

spl_autoload_register ('autoload');

function autoload ($className) {
    $fileName = $className . '.php';
    if (!file_exists($fileName)) {
        foreach (['controllers', 'models', 'templates'] as $folder) {
            // плохая практика переопределять переменную используемую в родительском $fileName
            $fileName = $folder. '/'. $className . '.php';
            if (file_exists($fileName)) {
                include $fileName;
                return;
            }
        }
    } else {
        include $fileName;
    }
}