<?php
if($data['user']['type']==0){
?>
<label for="auth-box">Авторизоваться</label>
<input type="checkbox" id="auth-box">
<div class="auth-box">
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
</div>
<?php
}else{
?>
Здравствуйте <strong><?=$data['user']['login'];?></strong>!
<a href="/logout">Выйти</a>
<?php
}
