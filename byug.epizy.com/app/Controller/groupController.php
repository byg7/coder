<?php
namespace Controller;
use System\Controller\Controller;
use System\Core\Request;
use System\Core\App;
use Model\groupForm;

class groupController extends defController{
  /**
   * @Route("/group")
   */
  function groupAction(Request $request, $query){
    if(App::getUser()['type']==0)return $this->render(array('template'=>'user/login'));
    $groupForm=new groupForm();
    $group=App::sessionGet('group');
    if($request->request('edit'))
      $group=$groupForm->getItem($request->request('edit'));
    $msg='';
    if($request->request('group')){
      $group=array(
        'group'=>$request->request('group'),
        'name'=>$request->request('name'),
        'flags'=>strtoupper(implode(array_keys($request->request('flags')))),
      );
      $msg=$groupForm->setItem(
        $request->request('old_group'),
        $request->request('group'),
        $request->request('name'),
        $request->request('flags')
      );
    }
    App::sessionSet('group',$group);
    return array(
      'msg'=>$msg,
      'group'=>$group,
      'activemenu'=>'/groups',
    );
  }
  /**
   * @Route("/groups")
   */
  function groupsAction(Request $request, $query, $grp){
    if(App::getUser()['type']==0)return $this->render(array('template'=>'user/login'));
    $groupForm=new groupForm();
    return array(
      'groups'=>$groupForm->getAll(),
      'activemenu'=>'/groups',
    );
  }
}