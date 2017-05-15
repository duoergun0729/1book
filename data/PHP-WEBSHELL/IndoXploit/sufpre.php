<form name="data" method="post">
<textarea name="res7ock" cols='50' rows='12' placeholder="Input your text here"></textarea><br><br>
<input  style="width:30%" name="suffix" placeholder="Input your prefix"><br>
<input  style="width:30%"  name="prefix" placeholder="Input your suffix"><br><br>
<input type="submit" name='res' value="GASCOK!!">
</form>
</center>
<?php
$suf	=$_POST['suffix'];
$pre	=$_POST['prefix'];
$url	=$_POST['res7ock'];
$gas	=$_POST['res'];

if($gas){
$e=explode("\r\n",$url);
	echo"<center><textarea cols='50' rows='12'>";
	foreach($e as $urls){
	echo ($suf).($urls).($pre).("\n");
	}
}
echo" </textarea>";
?>