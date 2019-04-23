<?php
function classLoader($class) {
//echo '$class ',$class,'<br/>';
    $classPath = str_replace('\\', '/', $class.'.php');
    if(file_exists(MODULE_ROOT.'Component/'.$classPath)){
        require_once MODULE_ROOT.'Component/'.$classPath;
        return;
    }
    if(file_exists(MODULE_ROOT.'Controller/'.$classPath)){
        require_once MODULE_ROOT.'Controller/'.$classPath;
        return;
    }
    if(file_exists(MODULE_ROOT.$classPath)){
        require_once MODULE_ROOT.$classPath;
        return;
    }
    if(file_exists(APP_ROOT.'Component/'.$classPath)){
        require_once APP_ROOT.'Component/'.$classPath;
        return;
    }
    if(file_exists(APP_ROOT.'Controller/'.$classPath)){
        require_once APP_ROOT.'Controller/'.$classPath;
        return;
    }
    if(file_exists(APP_ROOT.$classPath)){
        require_once APP_ROOT.$classPath;
        return;
    }
echo 'classLoader ',$classPath,'<br/>';
}
spl_autoload_register('classLoader');
function shutdown($t){
//  echo '<!-- All ',microtime()-$t,' sec -->';
  if(microtime()-$t>0.5){
    file_put_contents(__FILE__.'.wait',(microtime()-$t).' '.$_SERVER['REQUEST_URI']);
  }
/*  ob_end_flush();
  flush();
  time_sleep_until(time()+10);
  file_put_contents(__FILE__.'.stop',);
*/
}
$t=microtime();
register_shutdown_function('shutdown',$t);
//use System\Core\App;
/*
$blocks=array();
function useView($view, $param=null){
//file_put_contents(__FILE__.'.json',json_encode(array($view, $param)));
  global $data,$blocks;
  if(is_array($param))$data=$param;
  if(file_exists(APP_ROOT.
  (strlen(App::$module)==0?'':App::$module.'/').'View/'.App::$controller.
  '/'.$view.'.php')){
    $blocks[]=str_replace('/','_',
      (strlen(App::$module)==0?'':App::$module.'/').App::$controller.
      '/'.$view);
    include APP_ROOT.
      (strlen(App::$module)==0?'':App::$module.'/').'View/'.
      App::$controller.'/'.$view.'.php';
  }elseif(file_exists(APP_ROOT.
  (strlen(App::$module)==0?'':App::$module.'/').'View/'.$view.'.php')){
    $blocks[]=str_replace('/','_',
      (strlen(App::$module)==0?'':App::$module.'/').'/'.$view);
    include APP_ROOT.
      (strlen(App::$module)==0?'':App::$module.'/').'View/'.$view.'.php';
  }elseif(file_exists(APP_ROOT.'View/'.$view.'.php')){
    $blocks[]=str_replace('/','_',$view);
    include APP_ROOT.'View/'.$view.'.php';
  }elseif(file_exists(APP_ROOT.
  (strlen(App::$module)==0?'':App::$module.'/').'View/layout.php')){
    $blocks[]=str_replace('/','_',
      (strlen(App::$module)==0?'':App::$module.'/').'/layout');
    include APP_ROOT.
      (strlen(App::$module)==0?'':App::$module.'/').'View/layout.php';
  }elseif(file_exists(APP_ROOT.'View/layout.php')){
    $blocks[]=str_replace('/','_','layout');
    include APP_ROOT.'View/layout.php';
  }else{
    throw new \Exception('Template "'.$view.'" not found in /View/ or '.App::$module.'/View/');
  }
}
function noblock($block){
  global $data,$blocks;
  foreach($blocks as $item){
    if(function_exists($item.'_'.$block)){
      $fn=$item.'_'.$block;
      $fn();
      return false;
    }
  }
  return true;
}
  */