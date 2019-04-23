<?php
namespace System\Core;
class Request{
  public $get;
  public $post;
  public $files;
  private $params = array();
  public $request;
  function __construct(){
    $this->get = $_GET;
    $this->post = $_POST;
    $this->request = $_REQUEST;
    $this->files = $_FILES;
  }
  function get($name){
    if(isset($this->get[$name]) && $this->get[$name] != ''){
      return $this->get[$name];
    } else {
      if(isset($this->post[$name]) && $this->post[$name] != '') {
        return $this->post[$name];
      }
      return null;
    }
  }
  function post($name){
    if(isset($this->post[$name]) && $this->post[$name] != ''){
      return $this->post[$name];
    } else {
      return null;
    }
  }
  function request($name){
    if(isset($this->request[$name]) && $this->request[$name] != ''){
      return $this->request[$name];
    } else {
      return null;
    }
  }
  function isXmlHttpRequest(){
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
      return true;
    } else {
      return false;
    }
//    return 'XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH'];
  }
  function getParam($name){
    return $this->params[$name];
  }
  function setParam($params){
    if(is_array($params) && !empty($params)){
      foreach($params as $k => $v){
        $this->params[$k] = $v;
      }
    }
  }
  function add($params = array()){
    if(is_array($params) && !empty($params)){
      foreach($params as $k => $v){
        $this->get[$k] = $v;
      }
    }
  }
}