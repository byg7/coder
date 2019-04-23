<?php
use System\Core\App;
use Component\View;

//echo 1;
function template($template){return View::template($template);}
function startblock($name)  {return View::startblock($name);}
function endblock()         {return View::endblock();}
function echoblock($name)   {return View::echoblock($name);}
$this->rendered=true;
$dataDef=$this->getDefData();
$data=array_replace_recursive($dataDef,$data);
$template=isset($data['template'])?$data['template']:'';
if(count(explode('/',$template))>1){
  include template($template);
}else{
  include template(App::$controller.'/'.(strlen($template)==0?App::$action:$template));
}
