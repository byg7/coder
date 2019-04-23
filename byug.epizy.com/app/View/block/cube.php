<?php
if(!isset($data['cube']))return;
?>
<div class="cube-conteiner">
	<span class='title'>Куб ресурсов</span>
<?php
for($i=0;$i<6;$i++){
  $j=$i % count($data['cube']);
  echo "<a href='{$data['cube'][$j]['href']}' class='i{$i}' title='{$data['cube'][$j]['title']}' target='_blank'></a>
";
}
?>
	<div class="cube shadow">
<?php
for($i=0;$i<6;$i++){
  $j=$i % count($data['cube']);
  echo "<span class='n{$i}'>
 </span>";
}
?>
	</div>
	<div class="cube">
<?php
for($i=0;$i<6;$i++){
  $r=rand(0,3)*20+195;
  $g=rand(0,3)*20+195;
  $b=rand(0,3)*20+195;
  $j=$i % count($data['cube']);
  echo "<span class='n{$i}' style='background-color: rgb({$r},{$g},{$b});'>
  <img src='{$data['cube'][$j]['src']}' />
 </span>";
}
?>
	</div>
</div>
<script>
$(function(){
  var side=0;
  function nextCube(wait){
    $('.cube-conteiner.side'+side).removeClass('side'+side);
    side=Math.floor(Math.random() * 6);
    if(side>5)side=0;
    $('.cube-conteiner').addClass('side'+side);
    setTimeout(function(){
      nextCube(500+Math.floor(Math.random() * 4000));
    },wait);
  }
  nextCube(2000);
});
</script>
