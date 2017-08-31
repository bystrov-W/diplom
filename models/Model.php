class Model
{
    protected $pdo;
 
    public function __construct()
    {
        $dsn = 'mysql:dbname=test;host=127.0.0.1';
        $user = 'root';
        $pass = '';
 
        $this->pdo = new PDO($dsn, $user, $pass);
    }
}
