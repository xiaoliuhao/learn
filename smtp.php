<?php 
include("smtp.class.php");

$host = "smtp.qq.com";
$port = 25;
$user = "2014211750@stu.cqupt.edu.cn";
$pass = "qq470401911";

$from = "2014211750@stu.cqupt.edu.cn";
$to   = "470401911@qq.com";
$subject = "Hello Body";
$content ="This is example email for you";

$mail = new smtp_mail($host, $port, $user, $pass);
$flag = $mail->send_mail($from, $to, $subject, $content);
var_dump($flag);
echo '<pre>';
print_r($mail);
print_r($mail->sock);
var_dump($mail->sock);