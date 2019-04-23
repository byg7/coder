<?php
namespace Model;
use System\Core\App;
class pageForm{
  public function sqlAdd(){
    if(App::getUser()['type']==0){
      return ' join groups g on p.group=g.group and g.flags like \'%G%\'';
    }
    if(App::getUser()['type']==1){
      return ' join groups g on p.group=g.group and not g.flags like \'%A%\'';
    }
    return '';
  }
  public function getPage($grp, $slug){
    $sql=<<<sql
Select p.*
From pages p{$this->sqlAdd()}
Where p.`group`=:grp
  and replace(replace(replace(p.`slug`,':','_'),' ','_'),'''','_')=:slug
sql;
    $stmt =App::getPdo()->prepare($sql);
    $stmt->execute(array('grp'=>$grp,'slug'=>$slug));
    $res=$stmt->fetchAll();
    if(count($res)>0)return $res[0];
    return null;
  }
  public function loadPages($grp, $limit=20){
    $sql.=<<<sql
Select p.*,rand() as r, (select max(`name`) From groups n Where n.group=p.group) group_name
From pages p{$this->sqlAdd()}
Where p.`group` like :grp
order by r
limit $limit
sql;
    $stmt =App::getPdo()->prepare($sql);
    $stmt->execute(array('grp'=>$grp));
    $res=$stmt->fetchAll();
    foreach($res as $i=>$row)
      $res[$i]['slug']=str_replace(array("'",' ',':'),'_',$row['slug']);
    if(count($res)>0)return $res;
    return null;
  }
  public function loadPage($grp, $slug){
    $sql.=<<<sql
Select p.*
From pages p{$this->sqlAdd()}
Where p.`group`=:grp
  and replace(replace(replace(p.`slug`,':','_'),' ','_'),'''','_')=:slug
sql;
    $stmt =App::getPdo()->prepare($sql);
    $stmt->execute(array('grp'=>$grp,'slug'=>$slug));
    $res=$stmt->fetchAll();
    foreach($res as $i=>$row)
      $res[$i]['slug']=str_replace(array("'",' ',':'),'_',$row['slug']);
    if(count($res)>0)return $res[0];
    return 'Страница не найдена';
  }
  public function changePage($group_old=null,$slug_old=null,$group=null,$slug=null,$annotation=null,$text=null){
    if(is_null($group)){
      return;
    }
    if(strlen($annotation)==0){
      $annotation=strip_tags(substr($text,0,100),'<h1><h2><h3>');
    }
    $a=array(
      'group'=>$group,
      'slug'=>$this->updateSlug($slug),
      'annotation'=>$annotation,
      'text'=>$text,
      'datetime'=>date('Y-m-d H:i:s'),
    );
    if(is_null($group_old)){
      $sql.=<<<sql
Replace Into pages Set
 `group`=:group,
 `slug`=:slug,
 `annotation`=:annotation,
 `text`=:text,
 `date`=:datetime
sql;
    }else{
      $sql.=<<<sql
Update pages Set
 `group`=:group,
 `slug`=:slug,
 `annotation`=:annotation,
 `text`=:text,
 `date`=:datetime
Where `group`=:groupold and `slug`=:slugold
sql;
//return $sql;
      $a['groupold']=$group_old;
      $a['slugold']=$slug_old;
    }
    $stmt =App::getPdo()->prepare($sql);
    $stmt->execute($a);
    return 'Изменения внесены';
  }
  function updateSlug($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    $res=preg_replace("/^[^a-z0-9\s]*$/","",strtolower(strtr($string, $converter)));
    if(strlen($res)<3)return $res.time();
    return $res;
  }
}