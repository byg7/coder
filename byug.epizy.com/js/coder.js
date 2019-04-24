var CODER = {
	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
	showKeys: function (a) {
		// вычисляем y=(a0^a2) % a1
		// используем правило (a0^(a+b)=(a0^a)*(a0^b))
		var t=a[0];
		var r=String.fromCharCode(1);
		for (var i=0;i<a[2].length;i++) {
			var k=a[2].charCodeAt(i);
			for (var j=0;j<8;j++) {
				var c=k % 2;
				k=(k-c)/2;
				if (c==1)
					r=divKeys(multKeys(r,t),a[1],'ost');
				t=divKeys(multKeys(t,t),a[1],'ost');
			}
		}
		return r;
	},
	ascNewKey: function (keyLen) {
		var delitel=String.fromCharCode(CODER.rand(0, 255));
		for (var i=1;i<keyLen;i++) {
			delitel+=String.fromCharCode(CODER.rand(0, 255));
		}
		var osnovanie=String.fromCharCode(CODER.rand(0, 255));
		for (i=1;i<keyLen-1;i++) {
			osnovanie+=String.fromCharCode(CODER.rand(0, 255));
		}
		var zadumal=String.fromCharCode(CODER.rand(0, 255));
		for (i=1;i<keyLen-1;i++) {
			zadumal+=String.fromCharCode(CODER.rand(0, 255));
		}
		return [osnovanie, delitel, zadumal];
	},
	ordKeys: function (v) {
		var r='';
		for (var i=v.length-1;i>=0;i--) {
			r+=v.charCodeAt(i)+'.';
		}
		return r;
	},
	showKeys: function (a) {
		// вычисляем y=(a0^a2) % a1
		// используем правило (a0^(a+b)=(a0^a)*(a0^b))
		var t=a[0];
		var r=String.fromCharCode(1);
		for (var i=0;i<a[2].length;i++) {
			var k=a[2].charCodeAt(i);
			for (var j=0;j<8;j++) {
				var c=k % 2;
				k=(k-c)/2;
				if (c==1)
					r=CODER.divKeys(CODER.multKeys(r,t),a[1],'ost');
				t=CODER.divKeys(CODER.multKeys(t,t),a[1],'ost');
			}
		}
		return r;
	},
	addKeys: function (a1, a2) {
		var r='';
		var l=a1.length;
		if (l<a2.length)
			l=a2.length;
		irn=0;
		for (var i=0;i<l;i++) {
			if (i<a1.length) {
				var i1=a1.charCodeAt(i);
			} else {
				var i1=0;
			}
			if (i<a2.length) {
				var i2=a2.charCodeAt(i);
			} else {
				var i2=0;
			}
			// считаем данный разряд
			var ir=i1+i2+irn;
			var irm=ir % 256;
			var irn=(ir-irm)/256;
			r+=String.fromCharCode(irm);
		}
		if (irn>0) {
			r+=String.fromCharCode(irn);
		}
		return r;
	},
	multKeys: function (m1, m2) {
		if (m1==String.fromCharCode(1)) {
			return m2;
		} else if (m2==String.fromCharCode(1)) {
			return m1;
		} else if (m2==String.fromCharCode(0)) {
			return m2;
		} else if (m1==String.fromCharCode(0)) {
			return m1;
		} else {
	    	r=String.fromCharCode(0);
			o1='';
			// умножаем в столбик
			for (i=0;i<m1.length;i++) {
				o2=o1;
				for (j=0;j<m2.length;j++) {
					delta=m1.charCodeAt(i)*m2.charCodeAt(j);
					deltaStr=String.fromCharCode(delta % 256);
					deltaStr+=String.fromCharCode((delta-deltaStr.charCodeAt(0))/256);
					r=CODER.addKeys(r,o2+deltaStr);
					o2+=String.fromCharCode(0);
				}
				o1+=String.fromCharCode(0);
			}
			while ((r[r.length-1]==String.fromCharCode(0))&&(r.length>1)) {
				r=r.substr(0, r.length-1);
			}
			return r;
		}
	},
	subKeys: function (u, r) {
		if (r.length>u.length) {
			return '';
		} else {
			var z=0;
			var v='';
			for (var i=0;i<u.length;i++) {
				var ui=u.charCodeAt(i);
				if (i<r.length) {
					var ri=r.charCodeAt(i)+z;
				} else 
					var ri=z;
				if (ui<ri) {
					z=1;
					ui+=256;
				} else 
					z=0;
				v+=String.fromCharCode(ui-ri);
			}
			if (z==0) {
				while (v[v.length-1]==String.fromCharCode(0)) {
					v=v.substr(0, v.length-1);
				}
				return v;
			} else
				return '';
		}
	},
	divKeys: function (d1, d2, d3) {
		if (d2.length>d1.length) {
			if (d3=='ost') {
				return d1;
			} else if (d3=='all') {
				return [String.fromCharCode(0), d1];
			} else {
				return String.fromCharCode(0);
			}
		} else if (d2==String.fromCharCode(1)) {
			if (d3=='ost') {
				return String.fromCharCode(0);
			} else if (d3=='all') {
				return [d1, String.fromCharCode(0)];
			} else {
				return d1;
			}
		} else if (d1==String.fromCharCode(0)) {
			if (d3=='ost') {
				return d1;
			} else if (d3=='all') {
				return [d1, d1];
			} else {
				return d1;
			}
		} else if (d2.length==1) {
			var ost=0;
			var td2=d2.charCodeAt(0);
			var rez='';
			for (var i=d1.length-1;i>=0;i--) {
				var t=d1.charCodeAt(i)+ost*256;
				ost=t % td2;
				rez=String.fromCharCode((t - ost) / td2)+rez;
			}
			while ((rez[rez.length-1]==String.fromCharCode(0))&&(rez.length>1)) {
				rez=substr(rez, 0, -1);
			}
			if (d3=='ost') {
				return String.fromCharCode(ost);
			} else if (d3=='all') {
				return [rez, String.fromCharCode(ost)];
			} else {
				return rez;
			}
		} else {
			var m='';
			var i=d1.length-d2.length; // смещение
			var ost=d1.substr(i);
			while (i>=0) {
//console.log ("ost="+CODER.ordKeys(ost));
//console.log ("i="+i);
				var t=CODER.subKeys(ost, d2);
				var v=0;
//console.log ("i="+i);
				while (t!='') {
					v++;
					ost=t;
					t=CODER.subKeys(ost, d2);
//console.log ("ost="+CODER.ordKeys(ost));
				}
//console.log ("ost="+CODER.ordKeys(ost));
//console.log ("v="+v);
//console.log ("ost="+CODER.ordKeys(ost));
				if (v==0) {
					if (m!='') {
						m=String.fromCharCode(v)+m;
					}
				} else {
					m=String.fromCharCode(v)+m;
				}
				i--;
//console.log ("i="+i);
				if (i>=0) {
					ost=d1[i]+ost;
				}
			}
			while (ost[ost.length-1]==String.fromCharCode(0)) {
				ost=ost.substr(0, ost.length-1);
			}
			if (d3=='ost') {
				return ost;
			} else if (d3=='all') {
				return [m, ost];
			} else {
				return m;
			}
		}
	},
	/*	Функция для шифрования сообщения имеет следующие параметры:
		- input	- сообщение в Юникоде 16
		- passwd- пароль в Юникоде
		- hlen	- длина хешей
		результат в base64*/
	encodeFull: function (input, passwd, hlen) {
		return CODER.encodeFullBase64(CODER.encodeBase64(input).replace(/=/g,''), passwd, hlen);
	},
	/*	Функция для шифрования сообщения имеет следующие параметры:
		- input	- сообщение в base64
		- passwd- пароль в Юникоде
		- hlen	- длина хешей
		результат в base64*/
	encodeFullBase64: function (input, passwd, hlen) {
		var text=input;
//console.log ("text="+text);
		var pas=CODER._unicode_to_utf8(passwd);
		//ключ для шифрования
		var key=CODER.hash1(pas, hlen)+CODER.hash2(pas, hlen);
		key=key.replace(/=/g,'');
//console.log ("key="+key);
		//контрольная сумма сообщения
		var hash=CODER.hash1(text, hlen)+CODER.hash2(text, hlen);
		hash=hash.replace(/=/g,'');
		var tlen=CODER.rand(0, 63);
//console.log ("tlen="+tlen);
		var r=CODER.intToBase(tlen);
		var s=(tlen%15)+10;
		for (var i=0;i<s;i++) {
			r+=CODER.intToBase(CODER.rand(0, 63));
		}
		r+=text+hash;
		tlen=CODER.rand(0, 63);
//console.log ("tlen="+tlen);
		s=(tlen%15)+10;
		for (var i=0;i<s;i++) {
			r=CODER.encodeMix(r, key);
		}
		return CODER.intToBase(tlen)+r;
	},
	/*	Функция для дешифрования сообщения имеет следующие параметры:
		- input	- шифрованное сообщение в base64
		- passwd- пароль в Юникоде 16
		- hlen	- длина хешей
		результат в Юникоде 16*/
	decodeFull: function (input, passwd, hlen) {
		var r=CODER.decodeFullBase64(input, passwd, hlen);
		while ((r.length % 4)!=0) {
			r+='=';
		}
		return CODER.decodeBase64(r);
	},
	/*	Функция для дешифрования сообщения имеет следующие параметры:
		- input	- шифрованное сообщение в base64
		- passwd- пароль в Юникоде 16
		- hlen	- длина хешей
		результат в base64*/
	decodeFullBase64: function (input, passwd, hlen) {
		var pas=CODER._unicode_to_utf8(passwd);
		//ключ для шифрования
		var key=CODER.hash1(pas, hlen)+CODER.hash2(pas, hlen);
		key=key.replace(/=/g,'');
		var l=CODER.fromBASEint(input[0]);
//console.log ("l="+l);
		var s=(l%15)+10;
		var text=input.substr(1);
		for (var i=0;i<s;i++) {
			text=CODER.decodeMix(text, key);
		}
		l=CODER.fromBASEint(text[0]);
//console.log ("l="+l);
		s=(l%15)+10;
		text=text.substr(s+1);
//console.log ("text="+text);
		msg=text.substr(0, text.length-key.length);
		//контрольная сумма сообщения
		var hash=CODER.hash1(msg, hlen)+CODER.hash2(msg, hlen);
		hash=hash.replace(/=/g,'');
		if (text.substr(text.length-key.length)==hash) {
			return msg;
		} else {
			return 'LWVycg=='; // -err
		}
	},
	encodeBase64: function (input) {
		return CODER._utf8_to_base64(CODER._unicode_to_utf8(input));
	},
	decodeBase64: function (input) {
		return CODER._utf8_to_unicode(CODER._base64_to_utf8(input));
	},
	encodePack: function (input) {
		return CODER._utf8_to_pack(CODER._unicode_to_utf8(input));
	},
	decodePack: function (input) {
		return CODER._utf8_to_unicode(CODER._pack_to_utf8(input));
	},
	hash1: function (input, len) {
		var minsteps=len*50;
		if (input.length>minsteps)
			minsteps=input.length;
		var rez=[];
		for (var id=0;id<len;id++) {
			rez[id]=32;
		}
		var p=0;
		for (var id=0;id<minsteps;id++) {
			var c=rez[id % len];
			var k=input.charCodeAt(id % input.length);
			var r=c+k+p+id;
			rez[id % len]=r % 256;
			p=(r - r % 256) / 256;
		}
		var s="";
		for (var id=0;id<len;id++) {
			s=s+String.fromCharCode(rez[id]);
		}
		return CODER._utf8_to_base64(s);
	},
	hash2: function (input, len) {
		var minsteps=len*50;
		if (input.length>minsteps)
			minsteps=input.length;
		var rez=[];
		for (var id=0;id<len;id++) {
			rez[id]=32;
		}
		var p=0;
		for (var id=0;id<minsteps;id++) {
			var c=rez[id % len];
			var k=input.charCodeAt(id % input.length);
			var r=c+k+p+id;
			rez[id % len]=r % 256;
			p= r % 253;
		}
		var s="";
		for (var id=0;id<len;id++) {
			s=s+String.fromCharCode(rez[id]);
		}
		return CODER._utf8_to_base64(s);
	},
	encodeMix: function (text, key) {
		if (text.length==0) return text;
		var r=[]; // Преобразуем текст
		for (var i=0;i<text.length;i++) {
			r.push(text.charAt(i));
		}
		for (var i=0;i<r.length;i++) {
			var j=(key.charCodeAt(i%key.length)+i)%r.length;
			var c=r[i];
			r[i]=r[j];
			r[j]=c;
		}
		var s="";
		for (var i=0;i<r.length;i++) {
			s=s+r[i];
		}
		return s;
	},
	decodeMix: function (text, key) {
		if (text.length==0) return text;
		var r=[]; // Преобразуем текст
		for (var i=0;i<text.length;i++) {
			r.push(text.charAt(i));
		} 
		for (var l=1;l<=r.length;l++) {
			var i=r.length-l;
			var j=(key.charCodeAt(i%key.length)+i)%r.length;
			var c=r[i];
			r[i]=r[j];
			r[j]=c;
		}
		var s="";
		for (var i=0;i<r.length;i++) {
			s=s+r[i];
		}
		return s;
	}, /* Далее идут функции самообслуживания, не рекомендуется использовать*/
	_utf8_to_base64 : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;
		while (i < input.length) {
			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);
			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;
			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}
			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
		}
		return output;
	},
	_base64_to_utf8 : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;
		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
		while (i < input.length) {
			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));
			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;
			output = output + String.fromCharCode(chr1);
			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}
		}
//		output = CODER._utf8_decode(output);
		return output;
	},
	_unicode_to_utf8 : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
		for (var n = 0; n < string.length; n++) {
			var c = string.charCodeAt(n);
			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
		}
		return utftext;
	},
	_utf8_to_unicode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
		while ( i < utftext.length ) {
			c = utftext.charCodeAt(i);
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
		}
		return string;
	},
	intToBase: function (b) {
		if (b<26) {
			return String.fromCharCode(65+b);
		} else if (b<52) {
			return String.fromCharCode(97-26+b);
		} else if (b<62) {
			return String.fromCharCode(48-52+b);
		} else if (b==62) {
			return "+";
		} else {
			return "/";
		}
	},
	_utf8_to_pack: function (text) {
		function charToBin(c) {
			var code=c.charCodeAt(0);
			var r="";
			for (var i=0; i<8; i++) {
				if (code>=128) {
					r+="1";
					code=(code-128)*2;
				} else {
					r+="0";
					code=code*2;
				}
			}
//console.log ("c="+c.charCodeAt(0)+"->"+r);
			return r;
		}
		function toBase(bin) {
			var b=0;
			for (var i=0; i<6; i++) {
				b=b*2;
				if (bin.charAt(i)=="1") {
					b=b+1;
				}
			}
			if (b<26) {
				return String.fromCharCode(65+b);
			} else if (b<52) {
				return String.fromCharCode(97-26+b);
			} else if (b<62) {
				return String.fromCharCode(48-52+b);
			} else if (b==62) {
				return "+";
			} else {
				return "/";
			}
		}
		function goodChar(char) {
			if (char=="+") {
				return true;
			} else if (char=="/") {
				return true;
			} else if (char<="9") {
				return (char>="0");
			} else if (char<="Z") {
				return (char>="A");
			} else if (char<="z") {
				return (char>="a");
			} else
				return false;
		}
		// Если пустая строка, то она и есть пустая
		if (text.length==0) return text;
		// Если менее 4 символов и они входят в состав BASE, то тоже не кодируем
		if (text.length<4) {
			var ok=true;
			for (var i=0; i<text.length; i++) {
				if (ok)
					ok=goodChar(text.charAt(i));
			}
			if (ok)
				return text;
		}
		// Формируем таблицу веса каждого символа
		var chars=[];
		var charsL=0;
		for (var i=0; i<text.length; i++) {
			var key=text.charAt(i);
			if (typeof chars[key]=="undefined") {
				chars[key]=[];
				chars[key]["qty"]=1;
				charsL++;
			} else {
				chars[key].qty+=1;
			}
		}
		var o="";
		var r="";
		if (charsL==1) {
			// в (r) длина строки 1 и код символа повторяемый в строке
			var rr="";
			var i=text.length;
			while (i>255) {
				rr="1"+charToBin(String.fromCharCode(i%256))+rr;
				i=(i-i%256)/256;
			}
			r="001"+charToBin(String.fromCharCode(i%256))+rr+"0"+charToBin(text.charAt(0));
		} else {
			// формируем таблицу кодов и символов
			var keys=[];
			for (var e in chars) {
				keys[e]=[];
				keys[e]["qty"]=chars[e]["qty"];
				keys[e]["keys"]=[];
				keys[e]["keys"].push(e);
			}
			while (charsL>1) {
				// Ищем два минимальные и группируем в ноду
				var minE1='';
				var minE2='';
				var newKeys=[];
				charsL=0;
				// сначала первая нода
				for (var e in keys) {
					if (minE1.length==0) {
						minE1=e;
					}
					if (keys[minE1].qty>keys[e].qty) {
						minE1=e;
					}
				}
				// теперь вторая нода (при этом ноды большие сразу добавляем в новое дерево)
				for (var e in keys) {
					if (e!=minE1) {
						if (minE2.length==0) {
							minE2=e;
						} else if (keys[minE2].qty>keys[e].qty) {
							newKeys[minE2]=keys[minE2]; // Запишем в новый набор
							minE2=e;
							charsL++;
						} else {
							newKeys[e]=keys[e]; // Запишем в новый набор
							charsL++;
						}
					}
				}
				// новая нода
				var key=minE1+minE2;
				newKeys[key]=[];
				newKeys[key]["qty"]=keys[minE1]["qty"]+keys[minE2]["qty"];
				newKeys[key]["keys"]=[];
				for (var i=0; i<keys[minE1]["keys"].length; i++) {
					newKeys[key]["keys"].push("0"+keys[minE1]["keys"][i]);
				}
				for (var i=0; i<keys[minE2]["keys"].length; i++) {
					newKeys[key]["keys"].push("1"+keys[minE2]["keys"][i]);
				}
				keys=newKeys;
				charsL++;
			}
			r="";
			var showR1="";
			var showR2="";
			var chars=[];
			for (var e in keys) {
				for (var i=0; i<keys[e]["keys"].length; i++) {
					var t=keys[e]["keys"][i];
					var char=t.charAt(t.length-1);
					var key=t.substr(0, t.length-1);
					chars[char]=key;
					showR2+=char+"->"+key+" ";
					var p="";
					for (var j=0; j<key.length; j++) {
						if (t.charAt(j)=="0") {
							p+="0";
						} else {
							p="";
						}
					}
//console.log ("p="+p);
					r+=p+"1"+charToBin(char);
					showR1+=p+"1"+char;
				}
			}
			// сохраняем символы (кодами в (r) и как есть в (o))
			var ok=true;
			for (var i=0; i<text.length; i++) {
				o+=charToBin(text.charAt(i));
				r+=chars[text.charAt(i)];
				if (ok)
					ok=goodChar(text.charAt(i));
			}
//			console.log("o="+o);
//console.log("r="+r);
//			console.log("ok="+ok);
			var rr="";
			var i=text.length;
			while (i>255) {
				rr="1"+charToBin(String.fromCharCode(i%256))+rr;
				i=(i-i%256)/256;
			}
			rr=charToBin(String.fromCharCode(i%256))+rr;
//console.log("rr="+rr);
//			console.log("rr="+rr+", text.length="+text.length);
			r="1"+rr+r;
			o="01"+rr+"0"+o;
//			console.log("r.length="+r.length+", o.length="+o.length+", (text.length*6+6)="+(text.length*6+6));
			if (r.length>o.length) {
				if (ok) if (o.length>(text.length*6+6)) {
					return "A"+text;
				}
				r=o;
				o="0"; // не сжатый короче
			} else {
				if (ok) if (r.length>(text.length*6+6)) {
					return "A"+text;
				}
				o="1"; // сжатый короче
			}
		}
//		console.log("r="+r);
		// пакуем в BASE
		o=r.charAt(0);
		var rr="";
		for (var i=1; i<r.length;i++) {
			o+=r.charAt(i);
			if (o.length==6) {
				rr+=toBase(o);
				o="";
			}
		}
		// последний байт
		if (o.length>0) {
			while (o.length<6) {
				o+="0";
			}
			rr+=toBase(o);
		}
		return rr;
	},
	fromBASEint: function (char) {
		var b=char.charCodeAt(0);
		if (char=="+") {
			b=62;
		} else if (char=="/") {
			b=63;
		} else if (b>96) {
			b+=26-97;
		} else if (b>64) {
			b+=-65;
		} else {
			b+=52-48;
		}
		return b;
	},
	_pack_to_utf8:function (text) {
		function fromBASE(char) {
			var b=char.charCodeAt(0);
			if (char=="+") {
				b=62;
			} else if (char=="/") {
				b=63;
			} else if (b>96) {
				b+=26-97;
			} else if (b>64) {
				b+=-65;
			} else {
				b+=52-48;
			}
			var r="";
			for (var i=0; i<6; i++) {
				if (b>=32) {
					r+="1";
					b=(b-32)*2;
				} else {
					r+="0";
					b=b*2;
				}
			}
			return r;
		}
		function codeFromBin(bin) {
			var code=0;
			for (i=0; i<8; i++) {
				code+=code;
				if (bin.charAt(i)=="1")
					code++;
			}
			return code;
		}
		function rezLen() {
			var t=0;
			var addt="1";
			while (addt=="1") {
				t=t*256;
				var r="";
				for (var i=0; i<8; i++) {
					r+=o.charAt(n);
					n++;
				}
				t+=codeFromBin(r);
				addt=o.charAt(n);
//				console.log("t="+t+", addt="+addt+", r="+r);
				n++;
			}
			n--;
			return t;
		}
	// если меньше 4 символв, то так и есть
		if (text.length<4) return text;
		var o="";
		for (var i=0; i<text.length; i++) {
			o+=fromBASE(text.charAt(i));
		}
		var n=1;
		if (o.charAt(0)=="0") {
			if (o.charAt(1)=="0") {
				if (o.charAt(2)=="0") {
					// Не сжато все символы base64
					r="";
					for (var i=1; i<text.length; i++) {
						r+=text.charAt(i);
					}
					return r;
				} else {
					// Символ много раз
					n=3;
					var t=rezLen();
//					console.log("t="+t+", n="+n);
					n++;
					var c="";
					for (var j=0; j<8; j++) {
						c+=o.charAt(n);
						n++;
					}
					c=String.fromCharCode(codeFromBin(c));
					var r="";
					for (var j=0; j<t; j++) {
						r+=c;
					}
					return r;
				}
			} else {
				// Не сжато
				n=2;
				var t=rezLen();
				n++;
				var r="";
				while (r.length<t) {
					var c="";
					for (var i=0; i<8; i++) {
						c+=o.charAt(n);
						n++;
					}
					r+=String.fromCharCode(codeFromBin(c));
				}
				return r;
			}
		}
//		console.log("n="+n+", o="+o);
		var t=rezLen();
//		console.log("t="+t+", n="+n);
		var key="0";
		if (o.charAt(n)=="1") {
			n++;
			if (o.charAt(n)=="0") {
				// А тут всего один символ, но (t) раз
				var c="";
				for (j=0; j<8; j++) {
					n++;
					c+=o.charAt(n);
				}
				c=String.fromCharCode(codeFromBin(c));
				var r="";
				for (j=0; j<t; j++) {
					r+=c;
				}
				return r;
			} else {
				// Все символы и так в кодировке base64
				n+=6;
				var c="";
				for (j=0; j<8; j++) {
					n++;
					c+=o.charAt(n);
				}
				c=String.fromCharCode(codeFromBin(c));
				var r="";
				for (j=0; j<t; j++) {
					r+=c;
				}
				return r;
			}
		}
		var chars=[];
		var showR="";
		while (key.length>0) {
			n++;
			if (o.charAt(n)=="1") {
				var c="";
				for (j=0; j<8; j++) {
					n++;
					c+=o.charAt(n);
				}
				c=String.fromCharCode(codeFromBin(c));
				chars[key]=c;
				showR+=c+"->"+key+" ";
				while (key.charAt(key.length-1)=="1")
					key=key.substr(0,key.length-1);
				if (key.length>0) {
					key=key.substr(0,key.length-1)+"1";
				}
			} else {
				key+="0";
			}
		}
		var r="";
		key="";
		while (r.length<t) {
			n++;
			key+=o.charAt(n);
			if (typeof chars[key] != "undefined") {
				r+=chars[key];
				key="";
			}
		}
		return r;
	},
	rand: function (min, max) {
		return Math.floor(Math.random() * (max - min)) + min;
	}
}
