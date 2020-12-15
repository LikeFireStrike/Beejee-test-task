<?php
Class TaskModel extends Model
{
    /**
     * @var Array $safeAttrs Columns names with sort enabled
     */
    protected $safeSortAttrs   = ['id', 'name', 'email', 'status'];
    
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