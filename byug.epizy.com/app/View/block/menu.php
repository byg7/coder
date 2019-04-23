<?php
if(!function_exists('makeMenu')){
  function makeMenu($menu,$amenu){
    if(count($menu)<5){
      $str='<ul class="less">';
    }else{
      $str='<ul>';
    }
    $a=0;
    if(is_array($menu))foreach($menu as $k=>$v){
      if(is_array($v)){
        if(isset($v['menu'])){
          $sub=makeMenu($v['menu'],$amenu);
          $class='parent';
        }else{
          $sub=array(0,'');
          $class='';
        }
        if(($sub[0])or($k==$amenu)){
          $class=$class==''?'active':$class.' active';
          $a=1;
        }
        if(strlen($class)>0){
          $str.="<li class='$class'><a href='$k'>{$v['value']}</a>{$sub[1]}</li>\n";
        }else{
          $str.="<li><a href='$k'>{$v['value']}</a>{$sub[1]}</li>\n";
        }
      }else{
        $str.="<li><a href='$k'>$v</a></li>\n";
      }
    }
    $str.='</ul>';
    return array($a,$str);
  }
}
//var_dump($data);
$amenu=isset($data['activemenu'])?$data['activemenu']:'/';
$res=makeMenu($data['menu'],$amenu);
echo $res[1];
