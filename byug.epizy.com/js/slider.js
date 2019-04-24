function doSlider(elem,wait){
//alert(1);
	var stop=0;
	var items=[];
	var tWait=wait;
	jQuery(elem).find('.item').each(function(){
		items.push(this);
		jQuery(this).hide();
	});
	var sItem=items.length;
	function doWait(){
		if(stop==0){
			setTimeout(function(){
				tWait++;
				if(tWait>wait){
					var showNew=sItem+1;
					if(showNew>=items.length){
						showNew=0;
					}
					changeItem(showNew);
					tWait=5-Math.round(Math.random()*10);
				}
				doWait();
			},100);
		}
	}
	function changeItem(showNew){
		var showOld=sItem;
		sItem=showNew;
		var type=Math.round(Math.random()*100);
		if(type<20){
			jQuery(items[showOld]).css('opacity','0');
			jQuery(items[showNew]).css('display','block');
			jQuery(items[showNew]).css('opacity','1');
			tWait-=10;
			setTimeout(function() {
				jQuery(items[showOld]).css('display','none');
			}, 2000);
		}else{
			var toplft='top';
			var btmrgt='bottom';
			var start='-100%';
			var end='100%';
			if(type<60){
				toplft='left';
				btmrgt='right';
				if(type<40){
					start='100%';
					end='-100%';
				}
			}else if(type<80){
				start='100%';
				end='-100%';
			}
			jQuery(items[showNew]).css('display','block');
//			jQuery(items[showNew]).show();
			jQuery(items[showNew]).css('opacity','1');
			jQuery(items[showNew]).css(toplft,start);
			jQuery(items[showNew]).css(btmrgt,end);
			tWait-=10;
			setTimeout(function() {
				jQuery(items[showNew]).css(toplft,'0.5em');
				jQuery(items[showNew]).css(btmrgt,'0.5em');
				jQuery(items[showOld]).css(toplft,end);
				jQuery(items[showOld]).css(btmrgt,start);
				tWait-=10;
				setTimeout(function() {
					jQuery(items[showOld]).css(toplft,'0.5em');
					jQuery(items[showOld]).css(btmrgt,'0.5em');
					jQuery(items[showOld]).css('opacity','0');
					jQuery(items[showOld]).css('display','none');
				},1000);
			},1000);
		}
	}
	jQuery(elem).find('.prev').click(function(event){
		event.preventDefault();
		tWait=-30;
		var showNew=sItem-1;
		if(showNew<0){
			showNew=items.length-1;
		}
		changeItem(showNew);
	});
	jQuery(elem).find('.next').click(function(event){
		event.preventDefault();
		tWait=-30;
		var showNew=sItem+1;
		if(showNew>=items.length){
			showNew=0;
		}
		changeItem(showNew);
	});
	doWait();
	return {
		stop:function(){
			stop=1;
		}
	}
}
/*
bygSlider{
	constructor(elem,wait) {
		this.element = elem;
		this.tWait = wait;
		this.items=[];
	}
	go(){
		this.doWait();
	}
	doWait(){
		setTimeout(function() {
			tWait++;
			if(tWait>wait){
				var showNew=showedItem+1;
				if(showNew>=items.length){
					showNew=0;
				}
				changeItem(showNew);
				tWait=-Math.round(Math.random()*10);
			}
			doWait();
		},100);
	}
}
*/