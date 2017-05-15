<h3>Joomla</h3> 

<?php 
echo ' 
<form method="post" action="" enctype="multipart/form-data"> 
<input type="text" name="ip" value="" placeholder="Extract From ip" size="15"> <input type=submit name=get value=Get /> 
<br /> 
<textarea name="sites" cols="40" rows="13">'; 
if(isset($_POST['get']) && $_POST['ip'] != ""){ 
        $target = $_POST['ip']; 
        $sites = mbing("ip:$target index.php?option=com"); 
        if(!empty($sites)){ 
        $targets = implode("\n",cln_arr(array_map("jos_site",$sites))); 
        echo $targets; 
        }else{ 
            echo "No Joomla Found."; 
        } 
} 
echo '</textarea>'; 

function mbing($what){ 
    for($i = 1; $i <= 2000; $i += 10){ 
        $ch = curl_init(); 
        curl_setopt ($ch, CURLOPT_URL, "http://www.bing.com/search?q=".str_replace(" ","+", $what)."&first=$i"); 
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; U; CPU iPhone OS 3_0 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Version/4.0 Mobile/7A341 Safari/528.16"); 
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);     
        curl_setopt ($ch, CURLOPT_COOKIEFILE,getcwd().'/cookie.txt'); 
        curl_setopt ($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt'); 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1); 
        $data = curl_exec($ch); 
        preg_match_all('#<h2 class="sb_h3 cttl"><a href="(https?://.*?)" _ctf="rdr_T"#',$data, $links); 
        foreach($links[1] as $link){ 
            $allLinks[] = $link; 
        } 
        if(!preg_match('#class="sb_pagN"#',$data)) break; 
    } 
     
    if(!empty($allLinks) && is_array($allLinks)){ 
        return array_unique($allLinks); 
    } 
} 

function cln_arr($array){ 
    return @array_filter(@array_unique($array)); 
} 
function jos_site($site){ 
    return (preg_match("/option/",$site)) ? preg_replace("#(.*?)/index(.*)|(.*?)/?option(.*)#","$1/",$site):false; 
}