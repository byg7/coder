<?php
namespace Controller;
use System\Controller\Controller;
use System\Core\Request;
use System\Core\App;
use Model\sqlModel;

class sqlController extends defController{
  /**
   * @Route("/sql/{command}")
   */
  function sqlAction(Request $request, $query, $command){
    if(App::getUser()['type']==0)return $this->render(array('template'=>'user/login'));
    $sqlModel=new sqlModel();
    return array(
      'sql'=>$sqlModel->do($command),
    );
  }
}