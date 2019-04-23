<?php
if(!is_null($data['todo']))
  foreach($data['todo'] as $page){
    echo "
<a href='/page/{$page['group']}/{$page['slug']}' style='color:#000000;text-decoration:none;'>".
$page['annotation'].
'</a><br/>';
  }
