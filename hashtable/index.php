<?php
/**
 * Create by PhpStorm
 * Author: Liu  <470401911@qq.com>
 * Date: 2016/8/7
 * Time: 20:00
 * Version: learn
 */
include 'HashTable.class.php';
$ht = new HashTable();
$ht->insert('key1','value1'); //插入key1 = value1
$ht->insert('key2','value2'); //插入key2 = value2

echo $ht->find('key1'),PHP_EOL;
echo $ht->find('key2'),PHP_EOL;
