<?php
startblock('content');
?>
<style>
.viewer{
  width:100%;
}
.viewer table tr{
  padding:0;
  margin:0;
}
.viewer table td,
.viewer table th{
  padding:0.3em;
  margin:0;
  border:1px solid #dddddd;
}
.viewer .clone{
  position:fixed;
  top:0;
}
.viewer .clone,
.viewer table thead{
  background:#ffffee;
}
.selector{
  display:inline-block;
}
.editrow{
  width:99%;
  height:70vh;
}
#calceditor{
  width:100%;
  height:60vh;
}
</style>
<div class='selector'></div>
<label>Пароль<input type='password' id='pas4hide'/></label>
<button class='load'>Load</button>
<button class='edit'>Edit</button>
<button class='save'>Save</button>
<button class='savecalc' accesskey="s">Save</button>
<button class='savepasswd'>SavePassword</button>
<div class='viewer fixedfonts'></div>
<div id='calceditor' class='calceditor fixedfonts'></div>
<div class='loger fixedfonts' style='font-size:60%;'></div>
<script src="/js/jquery.stickytableheaders.js"></script>
<script src='/plugin/tinymce/tinymce.min.js'></script>

<link rel="stylesheet" type="text/css" href="/plugin/calc/socialcalc.css">
<script type="text/javascript" src="/plugin/calc/socialcalcconstants.js"></script>
<script type="text/javascript" src="/plugin/calc/socialcalc-3.js"></script>
<script type="text/javascript" src="/plugin/calc/socialcalctableeditor.js"></script>
<script type="text/javascript" src="/plugin/calc/formatnumber2.js"></script>
<script type="text/javascript" src="/plugin/calc/formula1.js"></script>
<script type="text/javascript" src="/plugin/calc/socialcalcpopup.js"></script>
<script type="text/javascript" src="/plugin/calc/socialcalcspreadsheetcontrol.js"></script>

<script src='/js/coder.js'></script>
<script>
$('.savepasswd').click(function(){
  if($(this).html()=='SavePassword'){
	localStorage.setItem('hiderowspassword', $('#pas4hide').val());
    $(this).html('DeletePassword');
  }else{
    localStorage.removeItem('hiderowspassword');
    $(this).html('SavePassword');
  }
});
$(function(){
  $('.save').hide();
  $('.edit').hide();
  $('.savecalc').hide();
  $('.calceditor').hide();
  $('.viewer').hide();
  if(localStorage.hiderowspassword){
    $('.savepasswd').html('DeletePassword');
    $('#pas4hide').val(localStorage.hiderowspassword);
    clickload();
  }
});
$('.load').click(function(){
  clickload();
});
function clickload(){
  id=0;
  $('.rowid').each(function(){id=$(this).val();});
  var passwd=$('#pas4hide').val();
  if(passwd.length<5){
    alert('Пароль должен быть не менее 5 символов '+passwd);
    return;
  }
  $('.selector').html('');
  $('.viewer').html('');
  $('.loger').html('Обращаюсь к серверу');
  $.ajax({
    type:"POST",
    url:'/hiderows/json',
    data:{
      type:'load',
      key:id
    },
    success:function(d){load(d);},
    dataType:'json'
  });
}
var id=0;
var stext=[];
var row='';
function load(d){
// сначала список
  $('.savecalc').hide();
  $('.save').hide();
  $('.calceditor').hide();
  $('.viewer').hide();
  $('.edit').show();
  $('.loger').html($('.loger').html()+'<br/>'+d.log);
  var r="<select class='rowid'>\n";
  if(d.k0.length==0){
    $('.selector').html(r+
      "<option value='1'>New category</option>\n</select>"
    );
    stext=['New category'];
    return;
  }
  var passwd=$('#pas4hide').val();
  stext=CODER.decodeFull(d.k0, passwd+'0', 32).split("|");
  r+="<option value='"+(stext.length+1)+"'>New category</option>\n";
  var r2='';
  var rstext=[];
  for(var i=0;i<stext.length;i++){
    rstext.push("no");
  }
  var j=0; // Сортируем по имени
  var sclassi='';
  var sclassj='';
  while(j>-1){
    j=-1;
    for(var i=0;i<stext.length;i++){
      if(rstext[i]=="no"){
        if(j==-1){
          j=i;
          if(stext[j].substr(0,1)=='*'){
            stextj=stext[j].substr(1,stext[j].length);
            sclassj='*';
          }else if(stext[j].substr(0,1)=='#'){
            stextj=stext[j].substr(1,stext[j].length);
            sclassj='#';
          }else{
            stextj=stext[j];
            sclassj='';
          }
        }else{
          if(stext[i].substr(0,1)=='*'){
            stexti=stext[i].substr(1,stext[i].length);
            sclassi='*';
          }else if(stext[i].substr(0,1)=='#'){
            stexti=stext[i].substr(1,stext[i].length);
            sclassi='#';
          }else{
            stexti=stext[i];
            sclassi='';
          }
          if(stexti<stextj){
            j=i;
            stextj=stexti;
            sclassj=sclassi;
          }
        }
      }
    }
    if(j>-1){
      rstext[j]="yes";
      if(id==j+1) {
        r2=sclassj;
        r=r+"<option value='"+(j+1)+"' selected='true'>"+sclassj+stextj+"</option>\n";
      }else{
        r=r+"<option value='"+(j+1)+"'>"+sclassj+stextj+"</option>\n";
      }
    }
  }
  r=r+"</select>";
  $('.loger').html($('.loger').html()+'<br/>Select: ok');
  $('.selector').html(r);
  if(id==0)return;
  if(!d['k'+id])return;
  setTimeout(function(){
    row=CODER.decodeFull(d['k'+id], passwd+id, 32);
    if(r2=='*'){
      $('.viewer').show();
      $('.viewer').html(row);
      return;
    }
    if(r2=='#'){
      $('.calceditor').show();
      if(!spreadsheet){
        spreadsheet = new SocialCalc.SpreadsheetControl();
        spreadsheet.InitializeSpreadsheetControl(
          "calceditor",$("#calceditor").height(),$("#calceditor").width(),0
        );
        spreadsheet.ExecuteCommand('redisplay', '');
      }
      var parts = spreadsheet.DecodeSpreadsheetSave(row);
      if(parts){
        if(parts.sheet){
          spreadsheet.sheet.ResetSheet();
          spreadsheet.ParseSheetSave(row.substring(parts.sheet.start, parts.sheet.end));
        }
        if(parts.edit){
          spreadsheet.editor.LoadEditorSettings(row.substring(parts.edit.start, parts.edit.end));
        }
      }
      if(spreadsheet.editor.context.sheetobj.attribs.recalc=="off"){
        spreadsheet.ExecuteCommand('redisplay', '');
      }else{
        spreadsheet.ExecuteCommand('recalc', '');
      }
      $('.savecalc').show();
      $('.viewer').hide();
      return;
    }
    $('.viewer').show();
    var a=row.split("\n");
    var r='<table><thead><tr><th>';
    for(var i=0;i<a.length;i++){
      if(i==0){
        r+=a[i].split('|').join('</th><th>')+'</th></tr></thead><tbody>';
      }else{
        r+='<tr><td>'+a[i].split('|').join('</td><td>')+'</td></tr>';
      }
    }
    r+='</tbody></table>';
    $('.viewer').html(r);
    $('.viewer').each(function(){moveTableHead(this)});
  },100);
}
function moveTableHead(cont){
  var clone=0;
  $(cont).find('table.clone').each(function(){clone=1;});
  if(clone==0){
    $(cont).find('table').each(function(){
      var html=$(this).html().split('</thead>');
      if(html.length>1){
        $(cont).html('<table class="clone">'+html[0]+'</thead></table>'+$(cont).html());
      }
    });
    $(cont).find('table.clone').each(function(){clone=1;});
    if(clone==0)return;
  }
  var cellsClone=$(cont).find('table.clone th,td');
  var cellsMain=$(cont).find('table.clone + table th,table.clone + table td');
  for(var i=0;i<cellsClone.length;i++){
    var wC=$(cellsClone[i]).width();
    var wM=$(cellsMain[i]).width();
    if(wC!=wM){
      $(cellsClone[i]).css({
        "min-width": (wC+(wM-wC)/1.2)+"px", 
        "width": (wC+(wM-wC)/1.2)+"px", 
        "max-width": (wC+(wM-wC)/1.2)+"px"
      });
    }
  }
  var bodyScrollTop=$('.body').scrollTop();
  var self=cont;
  $(cont).find('table').each(function(){
    self=$(this);
  });
  if((self.offset().top<0)
  && (self.offset().top+self.height()>0)){
    $(cont).find('table.clone').css('display','block');
    $(cont).find('table.clone').css('left',self.offset().left+'px');
  }else{
    $(cont).find('table.clone').css('display','none');
  }
  var next=0;
  $(cont).find('table.clone').each(function(){
    next=1;
  });
  if(next==1)setTimeout(function(){moveTableHead(cont)},500);
}
$('.edit').click(function(){
  id=0;
  $('.rowid').each(function(){id=$(this).val();});
  $('.viewer').show();
  $('.save').show();
  $('.calceditor').hide();
  $('.edit').hide();
  $('.selector').html('');
  var doctype=['','',''];
  var prefix='';
  var html=row;
  var ntext='Новый';
  if(stext.length>=id)ntext=stext[id-1];
  if(ntext.charAt(0)=='*'){
    doctype[1]='selected';
    ntext=ntext.substr(1,ntext.lengths);
    prefix='*';
  }else if(ntext.charAt(0)=='#'){
    doctype[2]='selected';
    ntext=ntext.substr(1,ntext.lengths);
    prefix='#';
  }else{
    doctype[0]='selected';
    html=row.split('|').join(' | ');
  }
  html='Новое название категории '+id+' "'+prefix+ntext+
    '": <input class="editname" value="'+
    ntext+'"> Тип документа <select class="doctype"><option value=" "'+doctype[0]+
    '>Таблица</option><option value="*"'+doctype[1]+
    '>HTML</option><option value="#"'+doctype[2]+
    '>Calc</option></select><br>Новое содержимое:<center><textarea class="editrow">'+
    html+'</textarea></center>';
  $('.viewer').html(html);
  $('.doctype').change(function(){openEditor($(this).val());});
  if(prefix=='*'){
    openEditor('*');
  }
});
var editorType=' ';
var spreadsheet='';
function openEditor(newEditorType){
  if(editorType=='*'){
    tinyMCE.remove();
  }
  editorType=newEditorType;
  if(editorType=='*'){
    tinymce.init({
      selector: 'textarea.editrow',
      height: 500,
      plugins: 'print preview powerpaste searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount tinymcespellchecker a11ychecker imagetools mediaembed linkchecker contextmenu colorpicker textpattern help',
      toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | pagebreak',
      image_advtab: true,
    });
  }
}
$('.savecalc').click(function(){
  var r=spreadsheet.CreateSpreadsheetSave();
  var passwd=$('#pas4hide').val();
  if(passwd.length<5){
    alert('Пароль должен быть не менее 5 символов '+passwd);
    return;
  }
  var val=stext.join('|');
  var valuekey=CODER.encodeFull(r, passwd+id, 32);
  var value=CODER.encodeFull(val, passwd+'0', 32);
  $.ajax({
    type:"POST",
    url:'/hiderows/json',
    data:{
      type:'save',
      key:id,
      value0:value,
      valuekey:valuekey
    },
    success:function(d){load(d);},
    dataType:'json'
  });
});
$('.save').click(function(){
  var r='';
  if(editorType=='*'){
    r=tinyMCE.get('textarea.editrow').getContent();
  }else{
    $('.editrow').each(function(){r=$(this).val();});
    r=r.split("	").join("|").split(" | ").join("|").split(" |").join("|").split("| ").join("|");
  }
  var passwd=$('#pas4hide').val();
  if(passwd.length<5){
    alert('Пароль должен быть не менее 5 символов '+passwd);
    return;
  }
  var name=$('.doctype').val();
  if(name==' '){
    name=$('.editname').val();
  }else{
    name+=$('.editname').val();
  }
  if(stext.length<id){
    stext.push(name);
  }else{
    stext[id-1]=name;
  }
  var val=stext.join('|');
  var valuekey=CODER.encodeFull(r, passwd+id, 32);
  var value=CODER.encodeFull(val, passwd+'0', 32);
  $.ajax({
    type:"POST",
    url:'/hiderows/json',
    data:{
      type:'save',
      key:id,
      value0:value,
      valuekey:valuekey
    },
    success:function(d){load(d);},
    dataType:'json'
  });
});
</script>
<?
endblock();
include template('layout');
