<?php
function addFlag($data, $text, $key){
?>
  <label>
    <span><?=$text;?></span>
    <span>
      <input type="checkbox" name="flags[<?=$key;?>]"<?=strpos($data['group']['flags'],$key)===false?'':'checked="checked"';?>>
      <div class="checkbox"></div>
    </span>
  </label>
<?php
}
startblock('content');
if(isset($data['group']['group'])){
  $group=$data['group']['group'];
  echo '<h2>Изменение категории ',$group,
    '</h2>';
}else{
  $group='';
  echo '<h2>Новая категория</h2>';
}
?>

<div class='form'><form method='post'>
  <input type="hidden" name="old_group" value="<?=$group;?>">
  <label>
    <span>Код</span>
    <span>
      <input type="text" name="group" value="<?=$group;?>">
    </span>
  </label>
  <label>
    <span>Название</span>
    <span>
      <input type="text" name="name" value="<?=$data['group']['name']?$data['group']['name']:'';?>">
    </span>
  </label>
<?php
addFlag($data, 'Доступно всем', 'G');
addFlag($data, 'Только для администратора', 'A');
?>
  <button type="submit">Сохранить</button>
</form><form action="/groups">
  <button type="submit">В реестр</button>
</form></div>
<?php
endblock();
include template('layout');
