<?php
startblock('description');
echo <<<html
Записи Юрия Бондаренко.
3D модели созданные на остнове SweetHome3d.
html;
endblock();
startblock('content');
?>
<script type="text/javascript" src="/lib/big.min.js"></script>
<script type="text/javascript" src="/lib/gl-matrix-min.js"></script>
<script type="text/javascript" src="/lib/jszip.min.js"></script>
<script type="text/javascript" src="/lib/core.min.js"></script>
<script type="text/javascript" src="/lib/geom.min.js"></script>
<script type="text/javascript" src="/lib/batik-svgpathparser.min.js"></script>
<script type="text/javascript" src="/lib/jsXmlSaxParser.min.js"></script>
<script type="text/javascript" src="/lib/triangulator.min.js"></script>
<script type="text/javascript" src="/lib/viewmodel.min.js"></script>
<script type="text/javascript" src="/lib/viewhome.min.js"></script>
<style type="text/css">
/* The class of components handled by the viewer */
.buttons{
  font-size:80%;
}
@media (max-width:590px){
  .buttons{
    font-size:70%;
  }
}
#viewerOverlay{
  position:fixed !important;
}
#viewerCanvas{
  position:fixed !important;
  top:3em !important;
  left:1em !important;
  bottom:1em !important;
  right:1em !important;
  width: calc(100vw - 3em) !important;
  height: calc(100vh - 5em) !important;
}
#viewerControls{
  background:rgba(255,255,255,0.9);
  position:fixed !important;
  left: 0 !important;
  top:0 !important;
}
#viewerControls:after{
  content:' Уровень ';
}
#viewerControls > select{
  float:right;
}
#viewerOverlay > img{
  position:fixed !important;
  top:2em !important;
  right:0 !important;
  left: auto !important;
}
.buttons > div{
  display:inline-block;
  width:16em;
  height:10em;
  margin:.71em;
  position:relative;
}
.buttons > div > a > div{
  display:block;
  position:absolute;
  top:0;
  left:0;
  right:0;
  bottom:0;
  font-size:150%;
  background: #cccccc;
  background:radial-gradient(#ffffff, #f1e9d2,#f2dbd3);
  padding: .8em;
  border: 1px solid #777777;
  border-radius: 5em / 2em;
  overflow:hidden;
  -webkit-transition: all .61s ease-in-out;
  -moz-transition: all .61s ease-in-out;
  -o-transition: all .61s ease-in-out;
  -ms-transition: all .61s ease-in-out;
  transition: all .61s ease-in-out;
  filter: saturate(0.3);
}
.buttons a{
  color: #000000;
  text-decoration: none;
}
.buttons > div > a:focus > div,
.buttons > div > a:hover > div{
/*
  background: #eeeecc;
  background: radial-gradient(#ffffff, #ddddaa);
*/
  border-radius: 2em / .5em;
  filter: saturate(1);
  top:-25%;
  left:-30%;
  right:-30%;
  bottom:-25%;
  z-index:10;
  font-size:225%;
}
.buttons > div > a > div > img{
  max-width:90%;
  max-height:80%;
}
</style>
<div>
  <!-- Copy the following button in your page, updating the default.sh3d URL and parameters if necessary -->
  <!-- Mouse and keyboard navigation explained at 
       http://sweethome3d.cvs.sf.net/viewvc/sweethome3d/SweetHome3D/src/com/eteks/sweethome3d/viewcontroller/resources/help/en/editing3DView.html 
       You may also switch between aerial view and virtual visit with the space bar -->
  <!-- For browser compatibility, see http://caniuse.com/webgl -->
<?php
  function addButton($sh3d, $level=null,$levels=null){
    echo "viewHomeInOverlay('/sh/{$sh3d}.sh3d',\n",
      "{roundsPerMinute:1,\n",                            /* Rotation speed of the animation launched once home is loaded in rounds per minute, no animation if missing */ 
      "widthByHeightRatio: canvasRatio(),\n",             /* Size ratio of the displayed canvas */
      "navigationPanel: 'none',\n",                       /* Displayed navigation arrows, "none" or "default" for default one or an HTML string containing elements with data-simulated-key 
                                                             attribute set "UP", "DOWN", "LEFT", "RIGHT"... to replace the default navigation panel, "none" if missing */ 
      "aerialViewButtonText: 'Вид сверху',\n",            /* Text displayed for aerial view radio button, no radio buttons if missing */ 
      "virtualVisitButtonText: 'Посетитель',\n",          /* Text displayed for virtual visit radio button, no radio buttons if missing */
      isset($level)?"level: '{$level}',\n":'',            /* Uncomment to select the displayed level, default level if missing */
      is_array($levels)?
        ('selectableLevels: '.
          str_replace(['[',',',']','"'],["[\n",",\n","\n]","'"],json_encode($levels)).
          ",\n"):'',/* Uncomment to choose the list of displayed levels, no select component if empty array */
   /* camera: "Exterior view", */                         /* Uncomment to select a camera, default camera if missing */
   /* selectableCameras: ["Exterior view", "Kitchen"], */ /* Uncomment to choose the list of displayed cameras, no camera if missing */
      "activateCameraSwitchKey: true,\n",                 /* Switch between top view / virtual visit with space bar if not false or missing */
      "viewerControlsAdditionalHTML: '',\n",              /* Additional HTML text appended to controls displayed below the canvas 3D, by default empty */
      "readingHomeText: 'Reading',\n",                    /* Comment displayed while reading home */
      "readingModelText: 'Model',\n",                     /* Comment displayed while reading models */
      "noWebGLSupportError: 'No WebGL support'\n",        /* Error message displayed if the browser do not support WebGL */
      "})\n";
  }
$buttons=array(
  'default'=>array('Дом с гаражем',null,null),
  'dom'=>array('Дом Д',null,array(
    '0 Зимний сад',
    '0 Топка',
    '1 этаж',
    '2 этаж пристр',
    'Крыша',
  )),
  'flat'=>array('Квартира Д',null,null),
  'plot'=>array('Участок',null,null),
  'balt'=>array('Квартира Б',null,null),
  'gagarin'=>array('Дом Г',null,null),
  'tavr'=>array('ЖК Таврический',null,null),
);
?>
  <center>
    3D проэкты домов, участков, квартир, офисов или производственных помещений:
    <div class='buttons'>
<?php
foreach($buttons as $key=>$button){
//  var_export($_SERVER['SERVER_ROOT'].'/sh/'.$key.'.png');
  $img=file_exists($_SERVER['SERVER_ROOT'].'/sh/'.$key.'.png')?"<br/><img src='/sh/{$key}.png'/>":'';
  echo <<<html
      <div><a id='b01' href='/3d/{$key}'><div>
        {$button[0]}{$img}
      </div></a></div>
html;
}
?>
    </div>
  </center>
<script>
<?php 
if(isset($data['id'])){
  addButton($data['id'],$buttons[$data['id']][1],$buttons[$data['id']][2]);
}
?>
function canvasRatio(){
  return (window.innerWidth-15)/(window.innerHeight-60);
}
</script>
<div style="text-align:center;width:95%;">
  <a href="http://www.sweethome3d.com">Sweet Home 3D</a> JS Viewer / Version 5.5 - Distributed under GNU General Public License
</div>
</div>
<?php
endblock();
include template('layout');
