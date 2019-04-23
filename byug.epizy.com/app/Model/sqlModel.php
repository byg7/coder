<?php
namespace Model;
use System\Core\App;
class sqlModel{
  public function do($command){
    $sql='';
    switch ($command){
      case 'tables':
        $sql=<<<sql
SHOW TABLES
sql;
      break;
      case 'show-pages':
        $sql=<<<sql
SHOW CREATE TABLE pages
sql;
      break;
      case 'show-groups':
        $sql=<<<sql
SHOW CREATE TABLE groups
sql;
      break;
    }
    if($sql){
      $stmt =App::getPdo()->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll();
    }
    return $command;
  }
}