<?php
Class TaskModel extends Model
{
    /**
     * @var Array $safeAttrs Columns names with sort enabled
     */
    protected $safeSortAttrs   = ['id', 'name', 'email', 'status'];
    
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
            ':offset' => $offset,
            ':limit'  => $limit            
        ]);
        $dbStmnt->execute();
        return $dbStmnt->fetchAll();
    }
    
    /**
     * Update task record
     */
    public function updateById($id, $name, $email, $content, $status, $moderated)
    {
      if (!is_null(intVal($id))) {
          $dbStmnt = $this->db->prepare(
              "UPDATE ".$this->table." SET name=:name, email=:email, content=:content, status=:status, moderated=:moderated WHERE id=:id"
          );      
          $this->replacePlaceholders($dbStmnt, [
              ':id'        => $id,
              ':name'      => $name,
              ':email'     => $email,
              ':content'   => $content,
              ':moderated' => $moderated,
              ':status'    => $status
          ]);     
          return $dbStmnt->execute();
      } else {
          throw new InvalidArgumentException("Invalid sorting parameters!"); 
      }
    }
    
    /**
     * Create new record
     */
    public function createTask($name, $email, $content)
    {
        $dbStmnt = $this->db->prepare(
            'INSERT INTO '.$this->table.'(name, email, 
            content) VALUES (:name, :email, :content);'
        );
        $this->replacePlaceholders($dbStmnt, [
            ':name'    => $name,
            ':email'   => $email,
            ':content' => $content
        ]);
        return $dbStmnt->execute();
    }
}