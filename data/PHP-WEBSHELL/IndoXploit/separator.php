<form method='post'>
<textarea name='sites' cols='50' rows='12' placeholder="Input Your Text" ></textarea><br><br>
<td><input type="text" style="width:30%" " name="apa" placeholder="Mau Dipisah Dari Mana"><br><br>
<input type='submit' name='go' value='GASCOK'>
<input type='reset' value='CANCEL'><br>
</form></center>
<?php
error_reporting(0);
set_time_limit(0);
$ya=$_POST['go'];
$co=$_POST['sites'];
$apa=$_POST['apa'];

if  ($ya){
	$go=explode("\r\n",$_POST['sites']);
	echo"<center><textarea cols='50' rows='12'>";
	foreach($go as $url){
	$pisah = explode($apa,$url);
	echo "\r$pisah[0] ";
	}
echo"</textarea>";
	echo"<textarea cols='50' rows='12'>";
	foreach($go as $url){
	echo "\r$pisah[1] ";
	}
}
echo" </textarea>";
 ?>