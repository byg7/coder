<?php
startblock('description');
$an=isset($data['page']['annotation'])?strip_tags($data['page']['annotation']):'';
echo htmlspecialchars(<<<html
Записи Юрия Бондаренко.
{$an}.
html
);
endblock();
startblock('content');
  if(!is_null($data['page'])){
    if($data['edit']){
      $edit="<a class='edit' href='/edit/{$data['page']['group']}/{$data['page']['slug']}'>Edit</a>";
    }
    $data_text=str_replace(
      array('{allok}'),
      array('<i class="allok"></i>'),
      $data['page']['text']
    );
    echo "<div class='page fixedfonts'>
<div class='buttons'>{$edit}</div>
{$data_text}</div>";
  }
endblock();
startblock('head');
?>
  <style>
.allok{
  background:url(/img/allok.png) no-repeat;
  position:absolute;
  z-index:10;
  width:20em;
  height:20em;
  transform: scale(0.5) translate(0,-15em) rotate(10deg);
  display:inline-block;
}
  </style>
  <script src="/js/highlight/highlight.pack.js"></script>
  <script>hljs.initHighlightingOnLoad();</script>
  <!--link rel="stylesheet" href="/js/highlight/styles/xcode.css"-->
  <link rel="stylesheet" href="/js/highlight/styles/github.css">
<?php
endblock();
include template('layout');
