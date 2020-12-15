<?php
/**
 * Database singleton class.
 * @property PDO $connection A valid PDO object
 * @property Database $instance class instance.
 */
class Database
{
    /**
     * @var Database $instance class instance.
     */
     private static $instance;
  
    /**
     * @var PDO $connection A valid PDO object
     */
    public $connection;
  
    /**
     * The constructor is private
     * to prevent initiation with outer code.
     * @param Array $config Configuration options array
     * @throws PDOException
     */ 
    private function __construct($config)
    {
        try {
            $this->connection = new PDO('mysql:host='.$config["host"]
                                        .';dbname='.$config["dbname"],
                                        $config["username"],
                                        $config["password"]);
        } catch (PDOException $e){
            echo $e->getMessage();
            die();
        }
    }
 
    /**
     * The object is created from within the class itself
     * only if the class has no instance.
     * @param array $config Configuration options array
    */
    public static function getInstance($config)
    {
        if (self::$instance == null)
        {
            self::$instance = new Database($config);
        }

        return self::$instance;
    }
}