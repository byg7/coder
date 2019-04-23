<?php
namespace Controller;
use System\Controller\Controller;
use System\Core\App;
use System\Cache\Cache;
use Model\pageForm;
use Model\groupForm;

class defController extends Controller{
  public function do4render($action, $arguments){
    $res=call_user_func_array(array($this, $action), $arguments);
    if(!is_array($res)){
      if(!$this->rendered)return $this->render($this->getDefData());
    }else{
      $data=$this->getDefData();
      $data=array_replace_recursive($data,$res);
      if(!$this->rendered)return $this->render($data);
    }
    return null;
  }
  protected function render($data = array()){
    include dirname(__FILE__).'/../defInclude.inc';
  }
  function getDefData(){
    $pageForm=new pageForm();
    $rss=Cache::loadFromCache('rss');
    if((!isset($rss['time']))or(($rss['time']+10000)>time())){
      $content=@file_get_contents('https://news.mail.ru/rss/economics/90/');
      if($content){
        Cache::createCache('content',$content);
        $array = @json_decode(json_encode(simplexml_load_string($content, null, LIBXML_NOCDATA)),TRUE);
        $rss=array('time'=>time(),'rss'=>$array);
        Cache::createCache('rss',$rss);
      }
    }
    return array(
      'menu'=>$this->getMenu(),
      'cube'=>$this->getBanner(0),
      'user'=>App::getUser(),
      'template'=>'',
      'todo'=>$pageForm->loadPages('%',5),
      'rss'=>$rss,
    );
  }
  function getMenu(){
    $menu=array(
      '/'=>'Главная',
      '/pages'=>array('value'=>'Статьи'),
      '/3d'=>array(
        'value'=>'Демонстрация',
        'menu'=>array(
          '/3d'=>array(
            'value'=>'3D Модели',
            'menu'=>array(
              '/3d/dom'=>array('value'=>'Дом'),
              '/3d/flat'=>array('value'=>'Квартира'),
            ),
          ),
          '/games'=>array(
            'value'=>'Игры',
            'menu'=>array(
              '/games'=>array('value'=>'Линии'),
            ),
          ),
        ),
      ),
    );
    $groupForm=new groupForm();
    $groups=$groupForm->getAll();
    $array=array();
    foreach($groups as $group)if($group['count_pages']>0){
      $levels=explode('-',$group['group']);
      $parent=substr($group['group'],0,-strlen($levels[count($levels)-1])-1);
      $parent=strlen($parent)==0?'page':$parent;
      $array[$parent][]=$group;
    }
    $menu['/pages']['menu']=$this->getMenu_Helper_getChildGroup($array,'page');
    if(App::getUser()['type']>0){
      $menu['/3d']['menu']['/hiderows']=array(
        'value'=>'Зашифрованные',
        'menu'=>array(
          '/hiderows'=>array('value'=>'Таблицы'),
          '/oldhiderows'=>array('value'=>'Старая версия'),
          '/hidedocs'=>array('value'=>'Документы'),
        ),
      );
    }
    if(!is_array($roles=App::getUser()['roles']))return $menu;
    if(in_array('admin',$roles)){
      $menu['/profile']=array(
        'value'=>'Профиль',
        'menu'=>array(
          '/new'=>array('value'=>'Создать'),
          '/users'=>array('value'=>'Пользователи'),
          '/groups'=>array('value'=>'Категории'),
        ),
      );
    }
    return $menu;
  }
  function getMenu_Helper_getChildGroup($array, $key){
    $menu=array();
    foreach($array[$key] as $item){
      $menu['/pages/'.$item['group']]=array(
        'value'=>$item['name'],
      );
      if(isset($array[$item['group']]))
        $menu['/pages/'.$item['group']]['menu']=
          $this->getMenu_Helper_getChildGroup($array, $item['group']);
    }
    return $menu;
  }
  function getBanner($id){
    $s=array(
      array(
        'href' =>'http://www.php.su/php/?php',
        'src'  =>'/img/PHP.png',
        'title'=>'Основы PHP',
      ),
      array(
        'href' =>'http://ntbcargo.ru',
        'src'  =>'/img/ntb.png',
        'title'=>'Таможенный Брокер',
      ),
      array(
        'href' =>'http://javascript.ru/',
        'src'  =>'/img/JS.png',
        'title'=>'Справочник по JavaScript',
      ),
      array(
        'href' =>'https://www.joomla.org/',
        'src'  =>'/img/joomla.png',
        'title'=>'CMS joomla',
      ),
      array(
        'href' =>'https://www.mysql.com/',
        'src'  =>'/img/MySQL.png',
        'title'=>'MySQL',
      ),
      array(
        'href' =>'http://www.makc.ru/',
        'src'  =>'/img/MAKS.png',
        'title'=>'Страховая компания',
      ),
    );
    $r=array();
    while($s){
      $l=array();
      foreach($s as $i=>$v){
        if(rand(0,9)>5){
          $r[]=$v;
        }else{
          $l[]=$v;
        }
      }
      $s=$l;
    }
    return $r;
  }
}
