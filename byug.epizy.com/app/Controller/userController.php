<?php
namespace Controller;
use System\Controller\Controller;
use System\Core\Request;
use System\Core\App;
use Model\userForm;

class userController extends defController{
  /**
   * @Route("/login")
   */
  function loginAction(Request $request, $query){
    if(App::getUser()['type']==0){
      $userForm=new userForm();
      $user=$userForm->setUser(
        $request->request('login'),
        $request->request('passwd')
      );
      if($user['type']==0)return;
    }
    return array(
      'activemenu'=>'/',
      'template'=>'logined',
    );
  }
  /**
   * @Route("/logout")
   */
  function logoutAction(Request $request, $query){
    App::sessionSet('user',array('type'=>0));
    return array(
      'activemenu'=>'/',
      'template'=>'login',
    );
  }
  /**
   * @Route("/profile")
   */
  function profileAction(Request $request, $query){
    if(App::getUser()['type']==0)return $this->render(array('template'=>'login'));
    $userForm=new userForm();
    return array(
      'msg'=>$userForm->changeUser(
          $request->request('login'),
          $request->request('passwd_old'),
          $request->request('passwd_new'),
          $request->request('passwd_rep')
        ),
      'activemenu'=>'/profile',
    );
  }
}