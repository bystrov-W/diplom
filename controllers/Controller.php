<?php
// файл Controller.php
class Controller
{
    protected $pdo;
    protected $view;
 
    public function __construct()
    {
        $dsn = 'mysql:dbname=test;host=127.0.0.1';
        $user = 'root';
        $pass = '';
 
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->view = new Template();
 
        //Редиректы
        //если не авторизован
        if (empty($_SESSION['user']) && $_SERVER['REQUEST_URI'] == '/question/index' OR empty($_SESSION['user']) && $_SERVER['REQUEST_URI'] == '/question-edit.php') {
            header( 'Location: /user/login' );
        }
        //если авторизован
        if (!empty($_SESSION['user']) && $_SERVER['REQUEST_URI'] == '/user/login') {
            header( 'Location: /question/index' );
        }
    }
}
 
 
?>
