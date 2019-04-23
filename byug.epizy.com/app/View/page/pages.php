<?php
startblock('description');
$name=isset($data['pages'][0]['group_name'])?$data['pages'][0]['group_name']:'';
echo htmlspecialchars(<<<html
Записи Юрия Бондаренко.
Список статей в разделе "{$name}".
html
);
endblock();
startblock('content');
if(!is_null($data['pages']))
  foreach($data['pages'] as $page){
    if($data['edit']){
      $edit="<a class='edit' href='/edit/{$page['group']}/{$page['slug']}'>Edit</a>";
    }
    echo "
<div class='preview'>
<div class='buttons'>{$edit}
<a class='show' href='/page/{$page['group']}/{$page['slug']}'>Show</a></div>
<a href='/page/{$page['group']}/{$page['slug']}' style='color:#000000;text-decoration:none;'>".
$page['annotation'].
//strip_tags($page['annotation'],'<h1><h2><h3>').
'</a></div>';
  }
endblock();
include template('layout');
