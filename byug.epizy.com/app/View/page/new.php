<?php
startblock('content');
echo "<h2>Редактор статей</h2>";
if(!is_null($data['msg']))echo "<div class='msg'>{$data['msg']}</div>";
?>
<script src='/plugin/tinymce/tinymce.min.js'></script>
<div class='form'><form method='post' action='/new'>
<?php
if(isset($data['data']['slug'])){
  echo "
<input type='hidden' name='group_old' value='{$data['data']['group']}'>
<input type='hidden' name='slug_old' value='{$data['data']['slug']}'>
";
}
?>
  <label>
    <span>Категория</span>
    <select name='group'>
<?php
foreach($data['groups'] as $group){
  $s=isset($data['data']['group'])?$data['data']['group']:'';
  $sel=$s==$group['group']?' selected="selected"':'';
  $p=preg_replace('/[^\-]/','',$group['group']);
  echo <<<html
      <option value="{$group['group']}"{$sel}>{$p}{$group['name']}</option>
html;
}
?>
    </select>
  </label>
  <label>
    <span>Путь</span>
    <input type='text' name='slug'<?php
if(isset($data['data']['slug'])){
  echo " value='{$data['data']['slug']}'";
}
?>>
  </label>
  <label>
    <span>Введение</span>
    <textarea id='text' name='annotation' class='annotation'><?php
if(isset($data['data']['annotation'])){
  echo $data['data']['annotation'];
}
?></textarea>
  </label>
  <label>
    <span>Статья</span>
    <textarea id='text' name='text' class='text'><?php
if(isset($data['data']['text'])){
//  echo $data['data']['annotation'],'<p><!-- pagebreak --></p>',$data['data']['text'];
  echo $data['data']['text'];
}
?></textarea>
  </label>
  <input type='submit' value='Сохранить'>
</form></div>
<script>
jQuery(document).ready(function(){
 tinymce.init({
  selector: 'textarea',
  height: '33vh',
  width: '100%',
  language: 'ru',
//  theme: 'inlite',
  plugins: 'print preview powerpaste searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount tinymcespellchecker a11ychecker imagetools mediaembed linkchecker contextmenu colorpicker textpattern help template code',
  toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | pagebreak | template',
  image_advtab: true,
  templates: [
    {title: 'Страхователь', description: 'ФИО страхователя', content: '{{INSURER_NAME}}'},
    {title: 'Застрахованный', description: 'ФИО застрахованного', content: '{{INSURED_NAME}}'}
  ],
 });
});
</script>
<?php
endblock();
include template('layout');
