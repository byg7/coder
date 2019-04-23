<?php
namespace System\Annotation;
use ReflectionClass;
use System\Cache\Cache;

class AnnotationParser{
  static function parse(){
    if($routes = Cache::loadFromCache('routing')) return $routes;
    $files = array();
    if((file_exists(APP_ROOT.'Controller/'))
    and(is_dir(APP_ROOT.'Controller/'))){
      $temp = scandir(APP_ROOT.'Controller/');
      foreach($temp as $file){
        if($file == '.' || $file == '..'){
        }else{
          $files['/'][] = $file;
        }
      }
    }
    $dirs=scandir(APP_ROOT.'Modules');
    foreach ($dirs as $dir){
      if(($dir != '.')and($dir != '..')
      and(file_exists(APP_ROOT.'Modules/'.$dir.'/Controller/'))
      and(is_dir(APP_ROOT.'Modules/'.$dir.'/Controller/'))){
        $temp = scandir(APP_ROOT.'Modules/'.$dir.'/Controller/');
        foreach($temp as $file){
          if($file == '.' || $file == '..'){
          }else{
            $files[$dir][] = $file;
          }
        }
      }
    }
//var_dump($files);
    foreach($files as $dir => $file){
      foreach($file as $k => $v){
        $controller = ($dir=='/'?'':$dir).'\\Controller'.'\\'.str_replace('.php', '', $file[$k]);
        $class = new ReflectionClass($controller);
        $classRoute = self::extractRoute($class->getDocComment());
        $actions = $class->getMethods();
        foreach($actions as $action){
          if($action->class == $class->name){
            $actionRoute = self::extractRoute($action->getDocComment());
            if(!empty($actionRoute)){
              $routes[] = array(
                $classRoute.$actionRoute => array(
                  'module' => $dir,
                  'controller' => $class->getShortName(),
                  'action' => $action->name
                )
              );
            }
          }
        }
      }
    }
    if(empty($routes)) return null;
    Cache::createCache('routing', $routes);
    return $routes;
  }
  private static function extractRoute($docComment){
//      preg_match('/[@Route("]{8}[\/a-zA-Z\-\_0-9]{0,100}[\/\{a-zA-Z\-\_0-9\}]{0,100}[\/")|")]/',
    preg_match('/(@Route\(")(\/[a-zA-Z\-\_0-9]{0,100}){1,100}(\/\{[a-zA-Z\-\_0-9]{1,100}\}){0,100}(\/"\)|"\))/',
      $docComment, $route);
    if(empty($route[0])) return '';
    $route[0]=str_replace(array('@Route("','")'),array('',''),$route[0]);
    return $route[0];
  }
  static function extractParam($route){
//    preg_match('/[{]{1}[a-zA-Z0-9\-\_\/]{1,100}[\}]{1}/', $route, $parameter);
    preg_match('/[{]{1}[a-zA-Z0-9\-\_\/]{1,100}[\}]{1}/', 
      str_replace('}/{','/',$route), $parameter);
    if(empty($parameter[0])) return '';
    return str_replace('/','}/{',$parameter[0]);
  }
  public function isMatch(){
    preg_match('/[@Route("]{8}[\/a-zA-Z_]{1,100}[\{a-zA-Z_\}]{0,100}["\)]{2}/', $this->classDoc, $class);
    if(empty($class[0])) return null;
    $class[0] = str_replace('@Route("', '', $class[0]);
    $class[0] = str_replace('")', '', $class[0]);
    if(!empty($this->methodDoc)){
      preg_match('/[@Route]{6}[(]{1}["]{1}[\/a-zA-Z_]{1,100}[\{a-zA-Z_\}]{0,100}["]{1}[\)]{1}/', $this->methodDoc, $method);
    }
    if(!empty($method[0])){
      $method[0] = str_replace('@Route("', '', $method[0]);
      $method[0] = str_replace('")', '', $method[0]);
    }else{
      $method[0] = '';
    }
    preg_match('/[{]{1}[a-zA-Z0-9\-\_]{1,100}[\}]{1}/', $this->methodDoc, $parameter);
    if(!empty($parameter[0])){
      $controllerRoute = str_replace($parameter, '', $class[0].$method[0]);
      $incomingRoute = $this->query;
      $parameter = str_replace($controllerRoute, '', $incomingRoute);
      if(is_numeric($parameter)){
        return 'match with param';
      }else{
        return 'no match with param';
      }
    }
    $controllerRoute = str_replace($parameter, '', $class[0].$method[0]);
    $incomingRoute = $this->query;
    $fullMatch = str_replace($controllerRoute, '', $incomingRoute);
    if($fullMatch == '' || $fullMatch == '/'){
      return 'match without param';
    }else{
      return 'no match without param';
    }
  }
}
