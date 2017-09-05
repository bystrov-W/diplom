<?php

class Route
{
    public static function start()
    {
        // по умолчанию
        $controller = 'question';
        $action = 'index';
        $routes = explode('/', $_SERVER['REQUEST_URI']);

        // имя контроллера
        if (!empty($routes[1]))
        {
            $controller = $routes[1];
        }

        // экшен и параметры
        if (!empty($routes[2]))
        {
            $actionUrl = explode('?', $routes[2]);
            $action = $actionUrl[0];
            // Эти же все параметры можно получить из переменной $_GET,
            // конечно, если особо в настройках веб сервиса не намудрить
            // Если уж парсить самостоятельно, то лучше использовать функцию parse_str(), ведь в этих параметрах
            // могут быть достаточносложные массивы.
            if (isset($actionUrl[1])) {
                $params = [];
                foreach (explode('&', $actionUrl[1]) as  $value) {
                    $indexAdnValue = explode('=', $value);
                    $params[$indexAdnValue[0]] = $indexAdnValue[1];
                }
            }
        }
        // префиксы
        $model = ucfirst($controller) . 'Model';
        $controller = ucfirst($controller) . 'Controller';
        $action .= 'Action';

        /*
         * Плохая практика завязывать роутер непосредственно на имена файлов: это не гибко и прямой способ атаки
         * перебирая имена файлов
         */

        // файла может и не быть
        $model_file = $model . '.php';
        $model_path = 'models/' . $model_file;
        if(file_exists($model_path))
        {
            include_once $model_path;
        }
        // файл с классом контроллера
        $controller_file = $controller . '.php';
        $controller_path = 'controllers/' . $controller_file;
        if(file_exists($controller_path))
        {
            include_once $controller_path;
        }

        // создаем контроллер
        $controller = new $controller;
        if(method_exists($controller, $action))
        {
            // вызываем действие контроллера
            (isset($params)) ? $controller->$action($params) : $controller->$action();
        }
    }
}
