<?php

session_start();

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('vendor/autoload.php');
// Не надо использовать два загрузчика, в composer.json можно указать какие дополнительные файлы надо включить в его
// автозагрузчик: https://getcomposer.org/doc/01-basic-usage.md#autoloading
/*
 * например:
  "autoload"         : {
    "psr-0"   : {
      "": "src/"
    },
    "psr-4"   : {
      "Tests\\"                         : "tests",
      "Xxx\\SDK\\Catalogue\\"       : "src/xxx/CatalogueSDK/src",
    },
    "classmap": [
      "app/AppKernel.php",
      "app/AppCache.php"
    ]
  },


*/
require_once 'autoload.php';

Route::start(); // запускаем маршрутизатор
