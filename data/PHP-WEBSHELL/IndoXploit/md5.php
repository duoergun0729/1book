<form name="data" method="post">
<textarea  name="res7ock" cols='50' rows='12'placeholder="Input your text"></textarea><br><br>
<input type="submit" name="res" value="GASCOK">
<input type="reset" value="CANCEL">

<?php
$ya=$_POST['res'];
$co=$_POST['res7ock'];
if($ya){
	echo"<br><br><textarea  cols='50' rows='12'>";
	echo  md5($co);
	echo"</textarea>";
}
?>