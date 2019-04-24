function moveTableHead(cont){
  $(cont).css('position','relative');
  var scTop=cont.scrollTop;
  $(cont).find('table').each(function(){
    if($(this).hasClass('clone')){
      $(this).css('top',scTop+'px');

    }else if(!$(this).hasClass('main')){
      var html=$(this).html().split('</thead>');
      if(html.length>1){
        $(cont).html('<table class="clone">'+html[0]+'</thead></table>'+$(cont).html());
      }
      $(this).addClass('main');
    }
  });
}
function old(){
  var scTop=cont.scrollTop;
  var tbls=cont.getElementsByTagName("table");
  for(var t=0;t<tbls.length;t++){
    var id=tbls[t].id;
    if(id.split("_").length==1){ /* нащел исходную таблу */
      var clone=cont.getElementById(id+"_clone");
      if(!clone){ /* создаем */
        var colgrp=tbls[t].getElementsByTagName("colgroup");
        if(colgrp.length>0)colgrp='<colgroup>'+colgrp[0].innerHTML+'</colgroup>'
        else colgrp='';
        var src=tbls[t].getElementsByTagName("thead");
        cont.innerHTML='<table id="'+id+'_clone">'+colgrp+src[0].outerHTML+'</table>'+cont.innerHTML;
        clone=document.getElementById(id+"_clone");
        clone.style["z-index"]="10";
      }
      for(var r=0;r<clone.rows.length;r++){
        for(var c=0;c<clone.rows[r].cells.length;c++){
          if(clone.rows[r].cells[c].offsetWidth!=
           tbls[t].rows[r].cells[c].offsetWidth){
            var pw=0;
            try{pw=clone.rows[r].cells[c].style['min-width'];
            }catch(e){}
            pw=(pw.replace('px','')*1);
            if(pw==0){
              pw+=tbls[t].rows[r].cells[c].offsetWidth;
            }else{
              pw+=((tbls[t].rows[r].cells[c].offsetWidth-
                clone.rows[r].cells[c].offsetWidth)/1.3);
            }
            clone.rows[r].cells[c].style['min-width']=pw+'px';
            clone.rows[r].cells[c].style['max-width']=pw+'px';
          }
        }
      }
      if(scTop<(clone.offsetHeight/10)){
        clone.style['top']=(-clone.offsetHeight)+'px';
        tbls[t].style['top']=(-clone.offsetHeight)+'px';
      }else{
        clone.style['top']=scTop+'px';
      }
    }
  }
}