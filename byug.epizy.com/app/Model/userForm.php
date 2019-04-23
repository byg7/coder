<?php
namespace Model;
use System\Core\App;
class userForm{
  public $login;
  public $passwd;
  public function setUser($login=null, $passwd=null){
    if(!is_null($login)) $this->login =$login;
    if(!is_null($passwd))$this->passwd=$passwd;
    if((!is_null($this->login))
    and(!is_null($this->passwd))){
      $sql=<<<sql
Select *
From users
Where login like :login and passwd like sha1(:passwd);
sql;
      $stmt =App::getPdo()->prepare($sql);
      $stmt->execute(array(
        'login'=>$this->login,
        'passwd'=>$this->passwd,
      ));
      $exist = $stmt->fetchAll();
      $user=array('type'=>0);
      if(isset($exist[0]['login'])){
        if(isset($exist[0]['roles'])){
          $user['type']=2;
          $user['roles']=explode(' ',$exist[0]['roles']);
        }else{
          $user['type']=1;
          $user['roles']=null;
        }
        $user['login']=$exist[0]['login'];
        $user['mail']=$exist[0]['mail'];
        $user['passwd']=$exist[0]['passwd'];
        App::sessionSet('user',$user);
      }
      return $user;
    }
  }
  public function changeUser($login=null, $passwd_old=null, $passwd_new=null, $passwd_rep=null){
    $a=array();
    if(is_null($login)){
      return;
    }
    if((!is_null($login))and(strlen($login)>0)){
      if($login!=App::getUser()['login']){
        $a['login']=$login;
      }
    }
    if((!is_null($passwd_old))and(strlen($passwd_old)>0)
    and(!is_null($passwd_new))and(strlen($passwd_new)>0)
    and(!is_null($passwd_rep))and(strlen($passwd_rep)>0)){
      if(sha1($passwd_old)!=App::getUser()['passwd']){
        return 'Не верный пароль';
      }
      if($passwd_new!=$passwd_rep){
        return 'Пароли не совпадают';
      }
      $a['passwd']=sha1($passwd_new);
    }
    if(count($a)>0){
      $sql="Update users ";
      $p='Set ';
      foreach($a as $k=>$v){
        $sql.="{$p}{$k}=:{$k}";
        $p=', ';
      }
      $sql.=" where mail=:mail";
      $a['mail']=App::getUser()['mail'];
      $stmt =App::getPdo()->prepare($sql);
      $stmt->execute($a);
      App::sessionSet('user',array_replace(App::getUser(),$a));
      return 'Изменения внесены';
    }
    return 'Нечего менять';
  }
}