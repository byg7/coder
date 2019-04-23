<?php
startblock('nonono');
include template('block/menu');
endblock();
startblock('content');
?>
<h1>Lines</h1>
<div class="conteiner"></div>
<button class="start">Новая игра</button>
<div class="bonus"></div>
<div class="record"></div>
<script>
$(function(){
  if(!localStorage.linesRecord)
    localStorage.setItem('linesRecord', 0);
  load();
  $('.start').click(function(){
    load();
  });
  function load(){
    var balls=[];
    var selected='';
    var bonus=0;
    var nr=0;
    var nc=0;
    $(".conteiner").html('wait...');
    var s='';
    for(var r=1;r<11;r++){
      for(var c=1;c<11;c++){
        s+='<div class="cell" id="r'+r+'c'+c+'"></div>'
      }
      s+='<div class="back"></div>'
    }
    $(".conteiner").html(s);
    newBalls();
    newBalls();
    newBalls();
    $('.bonus').html(0);
    $('.record').html(localStorage.linesRecord);
  function newBalls(){
    var i=0;
    nr=Math.floor(Math.random() * (10 - 1)) + 1;
    nc=Math.floor(Math.random() * (10 - 1)) + 1;
    var t=Math.floor(Math.random() * (8 - 1)) + 1;
    var k="r"+nr+"c"+nc;
    if(!balls[k]){
      $("#"+k).addClass('c'+t);
      balls[k]=t;
//      console.log("#"+k);
    }else{
      newBalls();
    }
  }
  $('.conteiner > .cell').click(function(){
    if(balls[this.id]){
      $(".selected").removeClass('selected');
      $(this).addClass('selected');
    }else{
      var tid=this.id;
      $(".selected").each(function(){
        var tc=tid.split('c');
        var tr=tc[0].split('r');
        tc=Math.floor(tc[1]);
        tr=Math.floor(tr[1]);
        var fid=this.id;
        var fc=fid.split('c');
        var fr=fc[0].split('r');
        fc=Math.floor(fc[1]);
        fr=Math.floor(fr[1]);
        var stepNo=[];
        var ballsStep=[];
        ballsStep[fid]=1;
        stepNo[0]=[[fc,fr]];
        if(findStep(0)){
          var steps=[];
          for(var p=0;p<100;p++){
            if(tc>1)if(getNo(tr,tc)>getNo(tr,tc-1)){steps.push('C+');tc=tc-1;}
            if(tc<10)if(getNo(tr,tc)>getNo(tr,1+tc)){steps.push('C-');tc=1+tc;}
            if(tr>1)if(getNo(tr,tc)>getNo(tr-1,tc)){steps.push('R+');tr=tr-1;}
            if(tr<10)if(getNo(tr,tc)>getNo(1+tr,tc)){steps.push('R-');tr=1+tr;}
            if(tr==fr)if(tc==fc)break;
          }
//          console.log(steps);
          doStep(steps.length-1);
          function doStep(p){
            var id='r'+tr+'c'+tc;
            var t=balls[id];
            $("#"+id).removeClass('c'+t);
            delete balls[id];
            if(steps[p]=='C+'){
              tc++;
            }
            if(steps[p]=='C-'){
              tc--;
            }
            if(steps[p]=='R+'){
              tr++;
            }
            if(steps[p]=='R-'){
              tr--;
            }
            id='r'+tr+'c'+tc;
            $("#"+id).addClass('c'+t);
            balls[id]=t;
            $(".selected").removeClass('selected');
            if(p>0){
              setTimeout(function(){
                doStep(p-1);
              },100);
            }else{
              iAdd=2;
              if(checkLines(tr,tc)==0)
                nextPart();
            }
          }
        }
        function findStep(i){
          stepNo[1+i]=[];
          if(stepNo[i].length==0)return 0;
          for(var iS=0;iS<stepNo[i].length;iS++){
            var c=stepNo[i][iS][0];
            var r=stepNo[i][iS][1];
            if(c==tc)if(r==tr)return 1;
            if(c>1)checkCell(r,c-1,1+i);
            if(c<10)checkCell(r,1+c,1+i);
            if(r>1)checkCell(r-1,c,1+i);
            if(r<10)checkCell(1+r,c,1+i);
          }
          return findStep(1+i);
        }
        function checkCell(r,c,i){
          var id='r'+r+'c'+c;
          if(!balls[id]){
            if(!ballsStep[id]){
              ballsStep[id]=1+i;
              stepNo[i].push([c,r]);
            }
          }
        }
        function getNo(r,c){
          var id='r'+r+'c'+c;
          if(ballsStep[id])return ballsStep[id];
          return 1000;
        }
        function nextPart(){
          iAdd=0;
          newBalls();
          checkLines(nr,nc);
          newBalls();
          checkLines(nr,nc);
          newBalls();
          checkLines(nr,nc);
        }
        var iAdd=0;
        function checkLines(r,c){
          var id='r'+r+'c'+c;
          var t=balls[id];
          var fd=[];
          checkLinesWs(r,c,1,0);
          checkLinesWs(r,c,1,1);
          checkLinesWs(r,c,0,1);
          checkLinesWs(r,c,1,-1);
//          console.log(['fd',fd]);
          if(fd.length>0){
//            console.log(['fd',fd]);
            for(d in fd){
              for(e in fd[d]){
                iAdd++;
                bonus+=iAdd;
                $("#"+e).removeClass('c'+fd[d][e]);
                delete balls[e];
                console.log([e,'c'+fd[d][e]]);
              }
            }
            $('.bonus').html(bonus);
            if(bonus>localStorage.linesRecord){
              localStorage.setItem('linesRecord', bonus);
              $('.record').html(bonus);
            }
            return 1;
          }else{
            return 0;
          }
          function checkLinesWs(r,c,dr,dc){
            var d=[];
            d[id]=t;
            checkLinesW(r+dr,c+dc,dr,dc);
            checkLinesW(r-dr,c-dc,-dr,-dc);
//            console.log(['d',Object.keys(d).length,d]);
            if(Object.keys(d).length>4){
//              console.log(['d',Object.keys(d).length,d]);
              fd.push(d);
            }
            function checkLinesW(r,c,dr,dc){
              var idW='r'+r+'c'+c;
              if(t==balls[idW]){
                d[idW]=t;
                checkLinesW(r+dr,c+dc,dr,dc);
              }
            }
          }
        }
      });
    }
  });
  }
});
</script>
<style>
.conteiner{
  border:1px solid #dddddd;
  font-size:2.5vh;
  padding:1em;
  height:31em;
  width:31em;
  display:block;
  float:left;
}
.conteiner > .cell{
  display:inline-block;
  width:0;
  height:0;
  padding:1.5em;
  border:1px solid #777777;
  margin:-1px;
  position:relative;
}
.conteiner > .back{
  display:block;
  margin:-3px;
}
.conteiner > .cell.selected{
  background:#dddddd;
}
.conteiner > .cell:after{
  position:absolute;
  width:0;
  height:0;
  padding:1em;
  border:1px solid #777777;
  border-radius:1em;
  top:0.5em;
  left:0.5em;
}
.conteiner > .cell.c1:after{
  content:' ';
  background:#aa0000;
}
.conteiner > .cell.c2:after{
  content:' ';
  background:#aaaa00;
}
.conteiner > .cell.c3:after{
  content:' ';
  background:#0000aa;
}
.conteiner > .cell.c4:after{
  content:' ';
  background:#aa00aa;
}
.conteiner > .cell.c5:after{
  content:' ';
  background:#00aaaa;
}
.conteiner > .cell.c6:after{
  content:' ';
  background:#aaaaaa;
}
.conteiner > .cell.c7:after{
  content:' ';
  background:#000000;
}
.bonus:before{
  content:'Счет: ';
}
.record:before{
  content:'Рекорд: ';
}
</style>
<?php
endblock();
include template('layout');
