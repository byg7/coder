<?php
use System\Annotation\AnnotationParser;
use System\Core\App;
require_once APP_ROOT.'System/Autoload.php';
class Router{
  public function run(){
    $query = explode('?',$_SERVER['REQUEST_URI']);
    $query = explode('#',$query[0]);
    if(!preg_match(Firewall, $query[0])){
//      throw new Exception('Router exception: incorrect url!');
      echo $query[0];
    }
    $this->execute($query[0]);
  }
  private function execute($query){
    $routes = AnnotationParser::parse();
    foreach($routes as $route){
      foreach($route as $path => $controllerAction){
        if($path == $query || $path == $query.'/' || $path.'/' == $query){
          $result = array(
            'path' => $query,
            'module' => $controllerAction['module'],
            'controller' => $controllerAction['controller'],
            'action' => $controllerAction['action'],
            'parameter' => null
          );
          $break = true;
          break;
        }
        if(isset($break) && $break == true) break;
      }
      if(isset($break) && $break == true) break;
    }
    if(empty($result)){
      foreach($routes as $route){
        foreach($route as $path => $controllerAction){
          $parameter = AnnotationParser::extractParam($path);
          $cleanPath = str_replace($parameter, '', $path);
          $match = str_replace($cleanPath, '', $query);
          $cleanRoute = str_replace($match, '', $query);
          if($cleanPath == $cleanRoute || $cleanPath.'/' == $cleanRoute || $cleanPath == $cleanRoute.'/'){
            if($query == '/'){
              $result = array(
                'path'      => '/',
                'module'    => $controllerAction['module'],
                'controller'=> $controllerAction['controller'],
                'action'    => 'indexAction',
                'parameter' => null
              );
            }else{
//echo "$path -> $cleanPath -> $query -> $cleanRoute, $match<br/>";
              if(count(explode('/',$parameter))==count(explode('/',$match))){
                if(preg_match('/^[0-9a-zA-Z-_\/]+$/', $match)){
                  $parameter=explode('/',$parameter);
                  $match=explode('/',$match);
                  $aMatch=array();
                  for($i=0;$i<count($match);$i++){
                    $aMatch[$parameter[$i]]=$match[$i];
                  }
                  $result = array(
                    'path'      => $cleanRoute,
                    'module'    => $controllerAction['module'],
                    'controller'=> $controllerAction['controller'],
                    'action'    => $controllerAction['action'],
                    'parameter' => $aMatch
                  );
                }
              }elseif(preg_match('/^[0-9a-zA-Z-_]+$/', $match)){
                $result = array(
                  'path'      => $cleanRoute,
                  'module'    => $controllerAction['module'],
                  'controller'=> $controllerAction['controller'],
                  'action'    => $controllerAction['action'],
                  'parameter' => $match
                );
              }
            }
          }
//else echo "else $path -> $cleanPath -> $query -> $cleanRoute, $match<br/>";
        }
      }
    }
//var_dump($result);
    // если маршрутов не найдено нигде, то отдадим строку запроса тому action
    // у которого в маршруте стоит '/'.
    // такой action должен быть только один на все бандлы
    if(empty($result)){
      foreach($routes as $route){
        foreach($route as $path => $controllerAction){
          if($path == '/'){
            $result = array(
              'path' => $query,
              'module' => $controllerAction['module'],
              'controller' => $controllerAction['controller'],
              'action' => $controllerAction['action'],
              'parameter' => $query
            );

            $break = true;
            break;
          }
          if(isset($break) && $break == true) break;
        }
        if(isset($break) && $break == true) break;
      }
    }

    if(!empty($result)){
      $controller = ($result['module']=='/'?'':'\\'.$result['module']).
        '\\Controller\\'.$result['controller'];
      $action = $result['action'];
      $parameter = $result['parameter'];
      $arguments = $this->getFunctionArguments($controller, $action, $parameter);

      App::$module = $result['module']=='/'?'':$result['module'];
      App::$controller = str_replace('Controller', '', $result['controller']);
      App::$action = substr($action,0,-6);
      (new $controller)->do4render($action,$arguments);
//      call_user_func_array(array(new $controller, $action), $arguments);
    }else{
      throw new Exception('Router exception: no route found!');
    }
  }

  private function frontRouter($query){
    $controller = new viewController();
    $action = 'indexAction';
    $query = explode('/', $query);
    foreach($query as $k => $v){
      if(empty($v)) unset($query[$k]);
    }
    $arguments = $this->getFunctionArguments($controller, $action, $query);
    call_user_func_array(array($controller, $action), $arguments);
  }

  private function getFunctionArguments($class, $method, $parameter = null){
//echo $class,'<br/>';
    $class = new ReflectionClass($class);
    $method = $class->getMethod($method);
    $parameters = $method->getParameters();
//var_dump($parameters);
    foreach($parameters as $p){
      if(method_exists($p, 'getClass')){
        if(property_exists($p, 'name')){
          if(isset($p->getClass()->name)){
            $data[] = array(
              $p->name => $p->getClass()->name
            );
          }else{
            $data[] = array(
              $p->name => null
            );
          }
        }
      }
    }
    if(empty($data)) return array();
    $arguments = array();
    foreach($data as $d){
      foreach($d as $name => $object){
        if($object){
          $class = new $object;
          if($class instanceof Entity){
            array_push($arguments, $class::getInstance('id', $parameter));
          }else{
            array_push($arguments, $class);
          }
        }else{
          if(isset($parameter['{'.$name.'}'])){
            array_push($arguments, $parameter['{'.$name.'}']);
          }else{
            array_push($arguments, $parameter);
          }
        }
      }
    }
    return $arguments;
  }
}