<?php
/**
 * Create by PhpStorm
 * Author: Liu  <470401911@qq.com>
 * Date: 2016/8/8
 * Time: 14:36
 * Version: learn
 * 用拉链算法有效解决了Hash值冲突的问题
 */
include 'NewHashTable.class.php';
$ht = new HashTable();
$ht->insert('key1','value1');
$ht->insert('key12','value12');

echo $ht->find('key1').PHP_EOL;
echo $ht->find('key12').PHP_EOL;