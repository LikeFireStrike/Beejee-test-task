<?php
Class Model
{
    /**
    * @var App $app Pointer to App object
    */
    private $app;
    
    /**
    * @var Int PER_PAGE Number of tasks to show on page
    */
    const PER_PAGE = 3;
    
    /**
    * @var String DIRECTIONS Sorting methods
    */
    const DIRECTIONS = ['ASC', 'DESC'];
    
    /**
     * @var PDO $db Database connection
     */
    protected $db;
    
    /**
     * @var Array $safeAttrs Columns whitelist 
     */
    protected $safeSortAttrs = [];
    
    /**
     * @var String $table Current table name 
     */
    protected $table;
    
    public function __construct($app, $table)
    {
        $this->app   = $app;
        $this->table = $table;
        $this->db    = $app->connection;
    }
    
    /**
     * Count items to apply pagination
     */
    public function countItems()
    {
        $dbStmnt = $this->db->prepare(
            'SELECT COUNT(*) FROM '.$this->table.';'
        );
        $dbStmnt->execute();
        return $dbStmnt->fetch();
    }
    
    public function selectPage($page = 1, $col = null, $direction = null)
    {
        $direction = strtoupper($direction);
        // Validate sorting attributes
        try {
                $this->white_list(
                $col,
                $this->safeSortAttrs,
                "Invalid field name"
            );
                $this->white_list(
                $direction,
                self::DIRECTIONS,
                "Invalid ORDER BY direction"
            );
        } catch (InvalidArgumentException $e) {
            echo $e->getMessage();
            die();
        }

        // Prepare the database query
        $dbStmnt = $this->db->prepare(
            'SELECT * FROM '.$this->table.' ORDER BY '
            .$col.' '.$direction.' LIMIT :limit OFFSET :offset'
        );
        $limit  = self::PER_PAGE;
        $offset = $limit * ($page - 1);
        $this->replacePlaceholders($dbStmnt, [
            ':limit'  => $limit,
            ':offset' => $offset
        ]);
        $dbStmnt->execute();
        return $dbStmnt->fetchAll();
    }
    
    protected function replacePlaceholders(&$dbStmnt, $params)
    {
        // Not need to reset array pointer, first usage in the scope
        while (($value = current($params)) !== false) {
            $placeholder = key($params);
            if(is_int($value))
                $option = PDO::PARAM_INT;
            elseif(is_bool($value))
                $option = PDO::PARAM_BOOL;
            elseif(is_null($value))
                $option = PDO::PARAM_NULL;
            elseif(is_string($value))
                $option = PDO::PARAM_STR;
            else
                $option = FALSE;
            $dbStmnt->bindValue($placeholder, $value, $option);
            next($params);
        }
    }
    /**
     * Check if value is in whitelist
     * @param Mixed $value
     * @param Array $allowed
     */
    private function white_list(&$value, $allowed, $message)
    {
        if ($value === null) {
            $value = $allowed[0];
        }
        $key = array_search($value, $allowed, true);
        if ($key === false) { 
            throw new InvalidArgumentException("Invalid sorting parameters!"); 
        }
    }
}