<form name="data" method="post">
<textarea name="res7ock" cols='50' rows='12' placeholder="Input Your Text"></textarea><br><br>
<input type="submit" name='res' value="GASCOK">
<input type="reset" value="CANCEL">
</form>
</center>
<?php
error_reporting(E_ALL ^ ( E_NOTICE | E_WARNING ));
$ya=$_POST['res'];
$co=$_POST['res7ock'];
$pecah=explode("\r\n",$co);
if($ya){
$hasil = array_unique($pecah);
	echo "<center><textarea  cols='50' rows='12'>".implode ($hasil,"\n")."</textarea>";
	}
?>