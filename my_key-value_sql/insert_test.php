<?php
/**
 * Create by PhpStorm
 * Author: Liu  <470401911@qq.com>
 * Date: 2016/8/9
 * Time: 0:42
 * Version: learn
 */
include 'DB.class.php';
$db = new DB();
$db->open('dbtest');

$start_time = explode(' ',microtime());
$start_time = $start_time[0] + $start_time[1];

for($i = 0; $i < 10000; $i ++) {
    $db->insert("key".$i, "value".$i);
}

$end_time = explode(' ',microtime());
$end_time = $end_time[0] + $end_time[1];

$db->close();
echo 'proccess time in '.($end_time - $start_time).'seconds';
//测试结果大概在2.8~3.2s之间