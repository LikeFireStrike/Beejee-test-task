<?php
/**
 * MVC without model can't exist, so should create empty class
 * Should add ability to read login and password 
 */
Class UserModel extends Model
{
  /**
  * Get user by name
  */
  public function getAdmin()
  {
    $dbStmnt = $this->db->prepare(
        "SELECT * FROM ".$this->table." WHERE login='admin'"
    );
    $dbStmnt->execute();
    return $dbStmnt->fetch();
  }
}