<?php
namespace Controller;
use System\Controller\Controller;
use System\Core\Request;
use System\Core\App;
use Model\hideForm;

class oldhideController extends defController{
  /**
   * @Route("/oldhiderows")
   */
  function oldhiderowsAction(Request $request, $query){
    if(App::getUser()['type']<2)return $this->render(array('template'=>'user/login'));
    return array(
      'activemenu'=>'/oldhiderows',
      'only'=>1,
    );
  }
}