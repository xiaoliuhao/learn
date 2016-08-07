<?php
/**
 * Create by PhpStorm
 * Author: Liu  <470401911@qq.com>
 * Date: 2016/8/7
 * Time: 19:56
 * Version: learn
 */
class HashTable{
    private $buckets;
    private $size = 10;

    /**
     * HashTable constructor.
     * 这里使用了SPL扩展的SplFixedArray数组而不是一般的数组(array)
     * 因为SplFixedArray数组更接近于C语言的数组,效率更高
     * 在创建SplFixedArray数组时需要为其提供一个初始化大小
     *
     * 要使用SplFixedArray数组必须安装并开启SPL扩展
     * php5.3或以后版本默认开启此扩展
     * 如果没有SPL扩展,可用一般数组(array)代替SplFixedArray
     */
    public function __construct() {
        $this->buckets = new SplFixedArray($this->size);
    }

    /**
     * hashfunc 最简单的hash算法,取余法
     * @access public
     * @param $key
     * @return int
     */
    private function hashfunc($key) {
        $strlen = strlen($key);
        $hashval = 0;
        for($i = 0; $i < $strlen; $i++){
            $hashval += ord($key{$i}); //ord返回字符的ASCII码值
        }
        return $hashval % $this->size;
    }

    /**
     * insert 插入数据
     * 先通过hashfunc函数计算关键字(索引)在Hash表中的位置
     * 然后把数据保存到此位置
     * @access public
     * @param $key
     * @param $val
     */
    public function insert($key, $val) {
        $index = $this->hashfunc($key);
        $this->buckets[$index] = $val;
    }

    /**
     * find 查找数据
     * 与插入数据方法相似
     * @access public
     * @param $key
     * @return mixed
     */
    public function find($key) {
        $index = $this->hashfunc($key);
        return $this->buckets[$index];
    }
}