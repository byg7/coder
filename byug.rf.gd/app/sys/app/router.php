<?php
class Router{
  public function run($path){
    ini_set('display_errors', 1);
    include __DIR__.'/app.php';
    include __DIR__.'/autoload.php';
    app::loadConfig($path.'/config.php');
    $url=explode('#',explode('?',$_SERVER['REQUEST_URI'])[0])[0];
    $base=app::base();
    if(substr($url,0,strlen($base))!=$base){
      app::useModule('', app::srcRoot());
      $controller=new DefaultController();
      $controller->doAction('index');
    }else{
      $controller=$this->getController(substr($url,strlen($base)));
    }


/*
    echo '<pre>';
    var_export($_SERVER);
    var_export(app::$params);
    echo '</pre>';
*/
  }
  private function getController($url,$path=app::srcRoot(),$module=''){
    echo  "<br/> Ищем контроллер для $url в $path";
    $array=explode('/',$url);
    if(file_exists($path.'/Modules/'.$array[0])){
      $this->getController(
        substr($url,strlen($array[0])+1),
        $path.'/Modules/'.$array[0],
        $module.'.'.$array[0]
      );
    }
    If(file_exists($path.'/Controllers/'.$array[0].'Controller.php')){
      app::useModule($module,$path);
      $controller=new DefaultController();
    }
  }
  
}
