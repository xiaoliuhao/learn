<?php
/**
 * Create by PhpStorm
 * Author: Liu  <470401911@qq.com>
 * Date: 2016/8/8
 * Time: 14:48
 * Version: learn
 */
$fp = fopen("data.dat","wb");
$bin = pack("L", 12);
fwrite($fp, $bin, 4);
fclose($fp);

$fp2 = fopen("data.dat","rb");
$bin = fread($fp2,4);
$pack = unpack("L",$bin);
fclose($fp2);

print_r($pack);

