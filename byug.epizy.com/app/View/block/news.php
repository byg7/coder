<?php
if(!is_null($data['rss']['rss']['channel']['item'])){
  foreach($data['rss']['rss']['channel']['item'] as $item){
    echo '<br/><a href="',$item['link'],
      '" target="_blank" rel="nofollow">',
      $item['title'],'</a>';
  }
//  echo '<pre>',var_export($data['rss']['rss']['channel']['item'],1),'</pre>';
}