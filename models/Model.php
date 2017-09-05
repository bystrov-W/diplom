<?php // эээ, почему здесь не было этой строчки?
class Model
{
    protected $pdo;
 
    public function __construct()
    {
        // Такие настройки надо выносить в конфиг и создавать подобные оъекты при инициализации приложения
        $dsn = 'mysql:dbname=test;host=127.0.0.1';
        $user = 'root';
        $pass = '';
 
        $this->pdo = new PDO($dsn, $user, $pass);
    }
}
