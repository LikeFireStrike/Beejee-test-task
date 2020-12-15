<?php
/**
 * The main application class
 */
Class App
{
    public $id;
    /**
     * Connection to database
     * @var PDO $connection The PDO object
     */
    public $connection;
    
    /**
     * @var String CONFIG_PATH Path to configuration file
     */
    const CONFIG_PATH = 'Config/config.ini';
    
    /**
     * @var String BASE_DIR Application home directory
     */
    const BASE_DIR = '/bj';
     
    public function __construct()
    {
        $dbInstance       = Database::getInstance($this->readConfig());
        $this->connection = $dbInstance->connection;
    }
    /**
     * Parse the ini file, cause exception on 
     * syntax or file not found error
     * @return Array Return array of configuration options
     * @throws Exception
     */
    private function readConfig() {
        try {
            if (file_exists(self::CONFIG_PATH)) {
                if (!($config = parse_ini_file(self::CONFIG_PATH))) {
                    throw new Exception("Config file syntax error");
                }
                // Return array of stored values if all are correct
                return $config;
            } else {
                throw new Exception("Config file not found!");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }
    
    /**
     * Entry point to the application
     */
    public function run()
    {
        session_start();
        $router     = new Router;
        $controller = $router->init($this);
        $controller->doAction();
    }
}