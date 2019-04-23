<?php
namespace System\Controller;
use System\Core\App;
use System\Modules\MySql\MySql;
use System\Core\ORM\ORM;

class Controller {
  protected $rendered;
  protected function getMysql(){
    return new MySql();
  }
  protected function getORM(){
    return new ORM();
  }
  public function do4render($action, $arguments){
    $res=call_user_func_array(array($this, $action), $arguments);
    if(!$this->rendered)$this->render($res);
  }
  protected function render($data = array()){
    $this->rendered=true;
    $template=isset($data['template'])?$data['template']:'';
    return useView(App::$controller.'/'.(strlen($template)==0?App::$action:$template),$data);
  }
  protected function redirectToRoute($route){
    header('Location:' . $route);
    return true;
  }
}
