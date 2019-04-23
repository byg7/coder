<?php
namespace Controller;
use System\Controller\Controller;
use System\Core\Request;
use System\Core\App;

class lastController extends defController{
  /**
   * @Route("/")
   */
  function indexAction(Request $request, $query){
    return array(
      'activemenu'=>'/',
    );
  }
  /**
   * @Route("/3d/")
   */
  function dddAction(Request $request, $query){
    return $this->dddIdAction($request, $query);
  }
  /**
   * @Route("/3d/{id}/{p}")
   */
  function dddIdPAction(Request $request, $query, $id=null, $p=null){
    return $this->dddIdAction($request, $query, $id);
  }
  /**
   * @Route("/3d/{id}")
   */
  function dddIdAction(Request $request, $query, $id=null){
    $data=array('id'=>$id);
    if(is_null($id)){
      $data['activemenu']='/3d';
      $data['item']='';
    }else{
      $data['activemenu']='/3d/'.$id;
      $data['item']=$query;
      $data['template']='ddd';
    }
    return $data;
  }
}