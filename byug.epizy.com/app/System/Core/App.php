<?php
namespace System\Core;
use PDO;
class App{
  static $module;
  static $controller;
  static $action;
  static $template;
  static $path;
  static $breadcrumbs;
  static $title;
  static $ctype = array();
  static private $db=null;
  static function getPdo(){
    if(is_null(self::$db)){
      try{
        self::$db = new PDO(
          MYSQL_HOST,
          MYSQL_LOGIN,
          MYSQL_PASSWORD,
          array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ));
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }catch (PDOException $e){
        self::$db=null;
      }
    }
    return self::$db;
  }
  static function sessionGet($name){
    if(strlen(session_id())==0){
      session_start();
    }
    if(isset($_SESSION[$name])){
      return $_SESSION[$name];
    }
  }
  static function sessionIsset($name){
    if(strlen(session_id())==0){
      session_start();
    }
    return isset($_SESSION[$name]);
  }
  static function sessionUnset($name){
    if(strlen(session_id())==0){
      session_start();
    }
    unset($_SESSION[$name]);
  }
  static function sessionSet($name,$value){
    if(strlen(session_id())==0){
      session_start();
    }
    $_SESSION[$name]=$value;
  }
  static function sessionClose(){
    if(strlen(session_id())>0){
      session_write_close();
    }
  }
  static function getUser(){
    if(strlen(session_id())>0){
      if(isset($_SESSION['user'])){
        return $_SESSION['user'];
      }
      return array('type'=>0);
    }
    if((isset($_REQUEST[session_name()]))
    or(isset($_COOKIE[session_name()]))){
      session_start();
      if(isset($_SESSION['user'])){
        return $_SESSION['user'];
      }else{
        return array('type'=>0);
      }
    }
  }
}