<?php
namespace Model;
use System\Core\App;
class hideForm{
  public function loadRows($key=0){
    $mail=App::getUser()['mail'];
    $sql=<<<sql
Select *
From hiderows
Where `mail` like :mail
  and (`key`=0 or `key`=:key)
sql;
    $stmt =App::getPdo()->prepare($sql);
    $stmt->execute(array(
      'mail'=>$mail,
      'key'=>$key,
    ));
    $exist = $stmt->fetchAll();
    $res=array('k0'=>'','log'=>'успешно загружено');
    foreach($exist as $row){
      $res['k'.$row['key']]=$row['value'];
    }
    return $res;
  }
  public function saveRows($key=0,$value0,$valuekey){
    $mail=App::getUser()['mail'];
    $sql=<<<sql
Replace Into hiderows (`value`, `mail`, `key`)
Select :value0, :mail, 0
union
Select :valuekey, :mail, :key
sql;
    $stmt =App::getPdo()->prepare($sql);
    $res=array(
      'k0'=>$value0,
      'k'.$key=>$valuekey,
      'log'=>'данные сохранены'
    );
    try{
      $stmt->execute(array(
        'mail'=>$mail,
        'key'=>$key,
        'value0'=>$value0,
        'valuekey'=>$valuekey,
      ));
    }catch(exception $e){
      $res=array('log'=>'не удалось сохранить: '.$e->getMessage());
    }
    return $res;
  }
}