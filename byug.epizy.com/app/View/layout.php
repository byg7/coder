<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <meta name='viewport' content='width=500, initial-scale=1'/>
  <meta name="description" content="<?php
if(!echoblock('description')){
?>Записи Юрия Бондаренко. Заметки, наработки, примеры кода, попытка структурировать накопленный опыт.<?php
}
?>"/>
  <meta name="yandex-verification" content="7cc7232a621aede2" />
  <link href="/img/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
  <link rel="stylesheet" href="/css/main.css?t=<?=date('ymdh');?>"/>
  <script src='/js/jquery-3.3.1.min.js'></script>
<?php if(!echoblock('head')){} ?>
  <title>
<?php if(!echoblock('title')){ ?>
В помощь программисту
<?php } ?>
  </title>
</head>
<body>
<script>
$(function(){
  function show(){
    $(".device").html($(window).width());
    setTimeout(function(){show()},5000);
  }
  show();
});
</script>
<div class='body<?=isset($data['only'])?' mainonly':'';?>'>
<div class="device" style="z-index:1000;"></div>
<?php if(!isset($data['only'])){ ?>
  <div class="left-block">
    <div class="logo">
      <div>
        <a href='/'><img src="/img/programming_logo.png" style="width:100%;" /></a>
      </div>
    </div>
    <div class="news">
      <input type="checkbox" class="show-news">
      <div>
        Новости
<?php include template('block/news');?>
      </div>
    </div>
  </div>
  <div class="right-block">
    <div class="cube">
      <div>
<?php include template('block/cube');?>
      </div>
    </div>
    <div class="todo">
      <input type="checkbox" class="show-todo">
      <div>
        Рекомендую
<?php include template('block/todo');?>
      </div>
    </div>
  </div>
<?php } ?>
  <div class="header">
    <div class='login'>
<?php include template('block/login');?>
    </div>
    <h1>
<?php if(!echoblock('title')){ ?>
В помощь программисту
<?php } ?>
    </h1>
    <div class='menu'>
<?php include template('block/menu');?>
    </div>
  </div>
  <div class="content">
    <?php
if(!echoblock('content')){
  if($data['content']){
    echo $data['content'];
  }else{
  echo 'Здесь будет контент';
  }
} ?>
  </div>
  <div class="footer">
      <a href='/'>© Юрий Бондаренко</a>
<!-- Yandex.Metrika informer -->
<a href="https://metrika.yandex.ru/stat/?id=51228847&amp;from=informer"
target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/51228847/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" class="ym-advanced-informer" data-cid="51228847" data-lang="ru" /></a>
<!-- /Yandex.Metrika informer -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter51228847 = new Ya.Metrika2({
                    id:51228847,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/tag.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks2");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/51228847" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->  </div>
  <script>
jQuery(document).ready(function(){
  if(!!('ontouchstart' in window)){
    var jsEmClick=0;
// отключаем первое срабатывание клика меню для touch device
    jQuery("a").click(function(event){
      if(!(jsEmClick==this)){
        event.preventDefault();
        jsEmClick=this;
      }else{
        jsEmClick=0;
      }
    });
  }
});
  </script>
</div>
</body>
</html>
