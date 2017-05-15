<form name="data" method="post">
<textarea  name="res7ock" cols='50' rows='12'placeholder="Input your text"></textarea><br><br>
<input type="submit" name="en" value="encode">
<input type="submit" name="de" value="decode" ><br><br>


<?php
#nemat0da
#res7ock crew
$md=$_POST['res7ock'];
if (isset($_POST['en'])){
	echo"<textarea  cols='50' rows='12'>";
	echo  base64_encode($md);
	echo"</textarea>";
}
else{
	echo"<textarea  cols='50' rows='12'>";
	echo  base64_decode($md);
	echo"</textarea>";
}
?>