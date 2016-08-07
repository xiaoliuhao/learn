<?php 
$sessionName = session_name();
//取得sessionID
$sessionID = $_GET[$sessionName];
//使用session_id()设置获得的Session
session_id($sessionID);
session_start();
print_r($_COOKIE);
var_dump($_SESSION);
