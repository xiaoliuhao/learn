<?php
/**
 * Create by PhpStorm
 * Author: Liu  <470401911@qq.com>
 * Date: 2016/8/8
 * Time: 14:11
 * Version: learn
 * Hash表冲突
 * 原因:
 * 不同的关键字通过Hash函数计算出来的Hash值相同.通过打印"key1"和"key12"的值可以发现他们都是8
 * 也就是说value1 和 value12 同时被存储在Hash表的第9个位置上 所以value1的值被value12覆盖了
 */
include 'HashTable.class.php';
$ht = new HashTable();
$ht->insert('key1','value1');
$ht->insert('key12','value12');

echo $ht->find('key1').PHP_EOL;
echo $ht->find('key12').PHP_EOL;