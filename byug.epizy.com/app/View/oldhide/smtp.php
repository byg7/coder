<?php
function get_data($smtp_conn){
  $data="";
  while($str = fgets($smtp_conn,515)){
    $data .= $str;
    if(substr($str,3,1) == " ") { break; }
  }
  return $data;
}
$header="Date: ".date("D, j M Y G:i:s")." +0700\r\n";
$header.="From: =?windows-1251?Q?".str_replace("+","_",str_replace("%","=",urlencode('Максим')))."?= <login@mail.ru>\r\n";
$header.="X-Mailer: The Bat! (v3.99.3) Professional\r\n";
$header.="Reply-To: =?windows-1251?Q?".str_replace("+","_",str_replace("%","=",urlencode('Максим')))."?= <login@mail.ru>\r\n";
$header.="X-Priority: 3 (Normal)\r\n";
$header.="Message-ID: <172562218.".date("YmjHis")."@mail.ru>\r\n";
$header.="To: =?windows-1251?Q?".str_replace("+","_",str_replace("%","=",urlencode('Сергей')))."?= <qwe@asd.ru>\r\n";
$header.="Subject: =?windows-1251?Q?".str_replace("+","_",str_replace("%","=",urlencode('проверка')))."?=\r\n";
$header.="MIME-Version: 1.0\r\n";
$header.="Content-Type: multipart/mixed; boundary=\"----------A4D921C2D10D7DB\"\r\n";

$file="path/1.jpg";
$fp = fopen($file, "rb");
$code_file1 = chunk_split(base64_encode(fread($fp, filesize($file))));
fclose($fp);
$code_file2=base64_encode("привет, это типа второй файл");

$text="------------A4D921C2D10D7DB
Content-Type: text/plain; charset=windows-1251
Content-Transfer-Encoding: 8bit

привет, это текст письма

------------A4D921C2D10D7DB
Content-Type: application/octet-stream; name=\"1.jpg\"
Content-transfer-encoding: base64
Content-Disposition: attachment; filename=\"1.jpg\"

".$code_file1."
------------A4D921C2D10D7DB
Content-Type: application/octet-stream; name=\"2.txt\"
Content-transfer-encoding: base64
Content-Disposition: attachment; filename=\"2.txt\"

".$code_file2."
------------A4D921C2D10D7DB--
";

$smtp_conn = fsockopen("smtp.mail.ru", 25,$errno, $errstr, 10);
if(!$smtp_conn) {print "соединение с серверов не прошло"; fclose($smtp_conn); exit;}
$data = get_data($smtp_conn);
fputs($smtp_conn,"EHLO mail.ru\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 250) {print "ошибка приветсвия EHLO"; fclose($smtp_conn); exit;}
fputs($smtp_conn,"AUTH LOGIN\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 334) {print "сервер не разрешил начать авторизацию"; fclose($smtp_conn); exit;}

fputs($smtp_conn,base64_encode("login")."\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 334) {print "ошибка доступа к такому юзеру"; fclose($smtp_conn); exit;}


fputs($smtp_conn,base64_encode("password")."\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 235) {print "не правильный пароль"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"MAIL FROM:login@mail.ru\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 250) {print "сервер отказал в команде MAIL FROM"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"RCPT TO:qwe@asd.ru\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 250 AND $code != 251) {print "Сервер не принял команду RCPT TO"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"DATA\r\n");
$code = substr(get_data($smtp_conn),0,3);
if($code != 354) {print "сервер не принял DATA"; fclose($smtp_conn); exit;}

fputs($smtp_conn,$header."\r\n".$text."\r\n.\r\n");
$code = <;/font>substr(get_data($smtp_conn),0,3);
if($code != 250) {print "ошибка отправки письма"; fclose($smtp_conn); exit;}

fputs($smtp_conn,"QUIT\r\n");
fclose($smtp_conn);
?>