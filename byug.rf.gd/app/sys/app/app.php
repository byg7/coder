<?php
class app{
	static private $params=array();
	static public function loadConfig($file){
		if(file_exists($file)){
			self::$params=array_merge_recursive(self::$params,require_once($file));
		}
		if(!isset(self::$params['app_sys'])){
			self::$params['app_sys']=dirname(__DIR__);
		}
		if(!isset(self::$params['app_root'])){
			self::$params['app_root']=dirname(self::$params['app_sys']);
		}
		if(!isset(self::$params['app_src'])){
			self::$params['app_src']=self::$params['app_root'].'/src';
		}
		if(!isset(self::$params['base'])){
      self::$params['base']='/';
		}
	}
	static public function __callStatic($method, $arguments){
		if(isset(self::$params['components'][$method]['class'])){
			$file=self::$params['app_sys'].'/'.str_replace('.','/',self::$params['components'][$method]['class']).'.php';
      if(file_exists($file)){
        include_once($file);
        $class=end(explode('.',self::$params['components'][$method]['class']));
        if(isset(self::$params['components'][$method]['params'])){
          return new $class(self::$params['components'][$method]['params']);
        }else{
          return new $class();
        }
      }
		}
    throw new Exception('На найден метод '.$method);
	}
  static public function base(){
    return self::$params['base'];
	}
  static public function moduleRoot(){
    return self::$params['module_root'];
	}
  static public function srcRoot(){
    return self::$params['app_src'];
	}
  static public function useModule($module, $path){
    self::$params['module']=$module;
    self::$params['module_root']=$path;
  }
}
