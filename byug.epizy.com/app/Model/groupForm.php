<?php
namespace Model;
use System\Core\App;
class groupForm{
  public function sqlAdd(){
    if(App::getUser()['type']==0){
      return ' where g.flags like \'%G%\'';
    }
    if(App::getUser()['type']==1){
      return ' where not g.flags like \'%A%\'';
    }
    return '';
  }
  public function getAll(){
    $sql=<<<sql
Select g.*, (select count(*) From pages p where p.`group`=g.`group`) as count_pages
From `groups` g{$this->sqlAdd()}
Order by g.`group`
sql;
    $stmt =App::getPdo()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }
  public function getItem($grp){
    $sql.=<<<sql
Select *
From `groups`
where `group` = :grp
sql;
    $stmt =App::getPdo()->prepare($sql);
    $stmt->execute(array('grp'=>$grp,));
    $res=$stmt->fetchAll();
//var_dump($res);
    if(count($res)>0)return $res[0];
    return null;
  }
  public function setItem($grp_old,$grp_new,$name,$flags){
    if(!$grp_new){
      return;
    }
    $a=array(
     'grp'=>$grp_new,
     'name'=>$name,
     'flags'=>strtoupper(implode(array_keys($flags))),
    );
    if($grp_old){
      $sql=<<<sql
Update `groups` Set
 `group` = :grp,
 `name` = :name,
 `flags` = :flags
Where `group`=:oldgrp
sql;
      $a['oldgrp']=$grp_old;
    }else{
      $sql=<<<sql
Replace into `groups` Set
 `group` = :grp,
 `name` = :name,
 `flags` = :flags
sql;
    }
    $stmt =App::getPdo()->prepare($sql);
    $stmt->execute($a);
    return 'Изменения внесены';
  }
}
