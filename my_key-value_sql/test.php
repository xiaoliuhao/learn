<?php
/**
 * Create by PhpStorm
 * Author: Liu  <470401911@qq.com>
 * Date: 2016/8/8
 * Time: 21:19
 * Version: learn
 */
function __hash($string) {
    $string = substr(md5($string), 0, 8);
    $hash = 0;
    for ($i = 0; $i < 8; $i++) {
        $hash += 33 * $hash + ord($string{$i});
    }
    return $hash & 0x7FFFFFFF;
}

function __pack(){
    $elem = pack('L',0x00000000);
    $str = '';
    for($i = 0; $i < 8; $i++) {
        $str .= $elem;
    }
//    var_dump($elem);
    var_dump($str);
    echo $str;
    $un_elem = unpack('L',substr($str, 0, 4));
    var_dump($un_elem);
    echo $un_elem[1];
}

function __fstat(){
    $fp = fopen("data.dat", "w+b");
    $idxoff = fstat($fp);
//    var_dump($idxoff);
    echo '<pre/>';
    print_r($idxoff);

    $int = intval(1.22);
    var_dump($int);
}

__fstat();