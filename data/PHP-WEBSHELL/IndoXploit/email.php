<center>
<body >
<form method="post">
        <textarea name="res" style="height:250px;width:500px" placeholder="MAXS 10.000 Email" ></textarea><br><br>
        <input type="submit" value="GASCOK!!" />
</form>

<?PHP
$date=date("d/m/Y");
if ($_POST['res']){
$res = $_POST['res'];
}
$ex = explode("\n",$res);
$count = count($ex);
if(isset($res)&&$count>=1){
echo "<center>	Ada <font color = 'red'> [ $count ] </font> Cok </center><br />";
}else{
echo "<center>	<font color = 'white'>masukkan Dulu Emailnya Cok </font></center>";
exit;}

if(isset($res)){
   

for($i=0;$i<=$count;$i++){
$d = strtolower($ex[$i]);

if(strstr($d,"hotmail")   || strstr($d,"live") || strstr($d,"msn") || strstr($d,"outlook")){
$hotmail.=$d;
$nh = $nh + 1;

}else{
if(strstr($d,"yahoo")   || strstr($d,"ymail")){
$yahoo.=$d;
$ny = $ny + 1;

}else{
if(strstr($d,"gmail")  || strstr($d,"googlemail")   ){
$gmail.=$d;
$ng = $ng + 1;

}else{
if(strstr($d,"aol")	){
$aol.=$d;
$na = $na + 1;

}else{
if(strstr($d,"mail.ru")	){
$mailru .=$d;
$nr = $nr + 1;

}else{
if(strstr($d,"wanadoo")	){
$wanadoo .=$d;
$nw = $nw + 1;
}else{

if(strstr($d,"ntlworld")	){
$ntlworld .=$d;
$nt = $nt + 1;

}else{
if(strstr($d,"gmx")	){
$gmx .=$d;
$ngm = $ngm + 1;

}else{
if(strstr($d,"@web.")	){
$web .=$d;
$nw2 = $nw2 + 1;

}else{

$other .=$d;
$nn=$nn + 1;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}               
?>
<center><table style="width: 30%">
 <tr>      
<td><center>Hotmail 	( <?echo $nh;?> ) 	</center><textarea 	name="hotmailx" cols="30" rows="10" ><?echo $hotmail;?>	</textarea></td>
<td><center>Gmail 		( <?echo $ng;?> )	</center><textarea 	name="gmailx" 	cols="30" rows="10" ><?echo $gmail;?>	</textarea></td>
<td><center>Aol 		( <?echo $na;?> )	</center><textarea 	name="aolxx" 	cols="30" rows="10" ><?echo $aol;?>		</textarea></td>
<td><center>Yahoo 		( <?echo $ny;?> )	</center><textarea	name="yahoox" 	cols="30" rows="10" ><?echo $yahoo;?>	</textarea></td>
<td><center>Mail.ru		( <?echo $nr;?> )	</center><textarea 	name="othersx" 	cols="30" rows="10" ><?echo $mailru;?>	</textarea></td></tr>
<tr>
<td><center>Wanadoo		( <?echo $nw;?> )	</center><textarea	name="othersx" 	cols="30" rows="10" ><?echo $wanadoo;?>	</textarea></td>
<td><center>Ntlworld	( <?echo $nt;?> )	</center><textarea 	name="othersx" 	cols="30" rows="10" ><?echo $ntlworld;?></textarea></td>
<td><center>Gmx			( <?echo $ngm;?> )	</center><textarea 	name="othersx" 	cols="30" rows="10" ><?echo $gmx;?>		</textarea></td>
<td><center>Web			( <?echo $nw2;?> )	</center><textarea 	name="othersx" 	cols="30" rows="10" ><?echo $web;?>		</textarea></td>
<td><center>Other mail	( <?echo $nn-1;?> )	</center><textarea 	name="othersx" 	cols="30" rows="10" ><?echo $other;?>	</textarea></td>                   
</tr>
</table>
</center>

?>
</body>