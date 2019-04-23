<?php
namespace Controller;
use System\Controller\Controller;
use System\Core\Request;
use System\Core\App;
use Model\pageForm;
use Model\groupForm;

class pageController extends defController{
  /**
   * @Route("/page/{grp}/{slug}")
   */
  function pageAction(Request $request, $query, $grp, $slug){
    $pageForm=new pageForm();
    return array(
      'page'=>$pageForm->loadPage($grp, $slug),
      'activemenu'=>'/pages/'.$grp,
      'template'=>'page',
      'edit'=>App::getUser()['type']>1,
      'only'=>1,
    );
  }
  /**
   * @Route("/new")
   */
  function newAction(Request $request, $query){
    return $this->editAction($request, $query);
  }
  /**
   * @Route("/edit/{grp}/{slug}")
   */
  function editAction(Request $request, $query, $grp=null, $slug=null){
    if(App::getUser()['type']==0)return $this->render(array('template'=>'user/login'));
    $pageForm=new pageForm();
    if(is_null($grp)){
      $menu='/new';
    }else{
      $menu='/pages/'.$grp;
    }
    $msg=$pageForm->changePage(
      $request->request('group_old'),
      $request->request('slug_old'),
      $request->request('group'),
      $request->request('slug'),
      $request->request('annotation'),
      $request->request('text')
    );
    if($msg=='Изменения внесены'){
      die($this->redirectToRoute("/page/{$request->request('group')}/".str_replace(array(' ',':'),array('_','_'),$request->request('slug'))));
    }
    $groupForm=new groupForm();
    return array(
      'msg'=>$msg,
      'groups'=>$groupForm->getAll(),
      'data'=>$pageForm->getPage($grp,$slug),
      'activemenu'=>$menu,
      'template'=>'new',
      'only'=>1,
    );
  }
  /**
   * @Route("/pages")
   */
  function allPagesAction(Request $request, $query){
    return $this->pagesAction($request, $query, '%');
  }
  /**
   * @Route("/pages/{group}")
   */
  function pagesAction(Request $request, $query, $group){
    $pageForm=new pageForm();
    return array(
      'pages'=>$pageForm->loadPages($group),
      'activemenu'=>$group=='%'?'/pages':'/pages/'.$group,
      'template'=>'pages',
      'edit'=>App::getUser()['type']>1,
      'only'=>1,
    );
  }
}