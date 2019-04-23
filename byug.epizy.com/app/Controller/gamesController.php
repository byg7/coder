<?php
namespace Controller;
use System\Controller\Controller;
use System\Core\Request;
use System\Core\App;

class gamesController extends defController{
  /**
   * @Route("/games")
   */
  function indexAction(Request $request, $query){
    return array(
      'activemenu'=>'/games',
      'only'=>1,
    );
  }
}
