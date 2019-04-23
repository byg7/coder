<?php
startblock('content');
?>
<form method='post' action='/login'>
  <label>
    <span>Логин</span>
    <input type='text' name='login'>
  </label>
  <label>
    <span>Пароль</span>
    <input type='password' name='passwd'>
  </label>
  <input type='submit' value='Войти'>
</form>
<?php
endblock();
include template('layout');
