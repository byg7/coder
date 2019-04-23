<?php
namespace Component;
use System\Core\App;
class View{
  static $blocks=array();
  static $block_name='';
  public static function template($template){
    $module=strlen(App::$module)==0?'':App::$module.'/';
    if(file_exists($path=APP_ROOT.$module.'View/'.App::$controller.'/'.$template.'.php')){
       return $path;
    }elseif(file_exists($path=APP_ROOT.$module.'View/'.$template.'.php')){
       return $path;
    }elseif(file_exists($path=APP_ROOT.'View/'.App::$controller.'/'.$template.'.php')){
       return $path;
    }elseif(file_exists($path=APP_ROOT.'View/'.$template.'.php')){
       return $path;
    }elseif(file_exists($path=APP_ROOT.$module.'View/layout.php')){
       return $path;
    }elseif(file_exists($path=APP_ROOT.'View/layout.php')){
       return $path;
    }else{
      throw new \Exception('Template "'.$template.'" not found in /View/ or '.App::$module.'/View/');
    }
  }
  public static function startblock($name){
    if(!isset(self::$blocks[$name])){
      self::$block_name=$name;
    }else{
      self::$block_name='notuse';
    }
    ob_start();
  }
  public static function endblock(){
    $content = ob_get_clean();
    self::$blocks[self::$block_name]=$content;
  }
  public static function echoblock($name){
    if(isset(self::$blocks[$name])){
      echo self::$blocks[$name];
      return 1;
    }else{
      return 0;
    }
  }
}
