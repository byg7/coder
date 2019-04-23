<?php
startblock('content');
echo "<h2>Ваш профиль</h2>";
if(!is_null($data['msg']))echo "<div class='msg'>{$data['msg']}</div>";
?>
<div class='form'><form method='post' action='/profile'>
  <label>
    <span>Логин</span>
    <input type='text' name='login' value='<?=$data['user']['login'];?>'>
  </label>
  <label>
    <span>Почта</span>
    <span><?=$data['user']['mail'];?></span>
  </label>
  <label>
    <span>Пароль</span>
    <input type='password' name='passwd_old'>
  </label>
  <label>
    <span>Новый пароль</span>
    <input type='password' name='passwd_new'>
  </label>
  <label>
    <span>Повтор</span>
    <input type='password' name='passwd_rep'>
  </label>
  <input type='submit' value='Изменить'>
</form></div>
<?php
endblock();
include template('layout');
