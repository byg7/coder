<?php
namespace Controller;
use System\Controller\Controller;
use System\Core\Request;
use System\Core\App;
use Model\hideForm;

class hideController extends defController{
  /**
   * @Route("/hiderows")
   */
  function hiderowsAction(Request $request, $query){
    if(App::getUser()['type']<2)return $this->render(array('template'=>'user/login'));
    return array(
      'activemenu'=>'/hiderows',
      'only'=>1,
    );
  }
  /**
   * @Route("/hiderows/json")
   */
  function hiderows_jsonAction(Request $request, $query){
    if(App::getUser()['type']<2){
      echo json_encode(array(
        'log'=>'Вы не авторизованы!!!'
      ));
    }else{
      $hideForm=new hideForm();
      if($request->request('type')=='load'){
        echo json_encode($hideForm->loadRows($request->request('key')));
      }elseif($request->request('type')=='save'){
        echo json_encode($hideForm->saveRows(
          $request->request('key'),
          $request->request('value0'),
          $request->request('valuekey')
        ));
      }
    }
    die();
  }
}