<?php
Class Model
{
    /**
    * @var App $app Pointer to App object
    */
    protected $app;
    
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
    
    /**
     * Set table name and database connection
     */
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
    
    /**
     * Get record by id
     */
    public function getById($id)
    {
        $dbStmnt = $this->db->prepare(
            'SELECT * FROM '.$this->table.' WHERE id=:id'
        );
        if (!is_null(intVal($id)))
          $this->replacePlaceholders($dbStmnt, [':id'=>$id]);
        else
          throw new InvalidArgumentException("Invalid select parameters!");
        $dbStmnt->execute();
        $record = $dbStmnt->fetchAll();
        return $record[0];
    }
    
    /**
     * Bind values to prepared query
     * @param PDOStatement &$dbStmnt
     * @param Array $params
     */
    protected function replacePlaceholders(&$dbStmnt, $params)
    {
        // Not need to reset array pointer, first usage in the scope
        while (!is_null(key($params))) {
            $placeholder = key($params);
            $value = current($params);
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
        return $dbStmnt;
    }
    /**
     * Check if value is in whitelist
     * @param Mixed $value
     * @param Array $allowed
     */
    protected function white_list(&$value, $allowed, $message)
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
