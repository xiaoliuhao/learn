<?php
/**
 * Create by PhpStorm
 * Author: Liu  <470401911@qq.com>
 * Date: 2016/8/8
 * Time: 14:25
 * Version: learn
 */

/**
 * Class HashNode
 * 拉链法解决冲突
 */
class HashNode{
    public $key;        //节点的关键字
    public $value;      //节点的值
    public $nextNode;   //具有相同Hash值节点的指针
    public function __construct($key, $value, $nextNode = NULL) {
        $this->key = $key;
        $this->value = $value;
        $this->nextNode = $nextNode;
    }
}

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
     * insert 拉链法下 插入数据
     * 先通过hashfunc函数计算关键字(索引)在Hash表中的位置
     * 如果此位置已经被其他节点占用 把新节点的$nextNode指向此节点
     * 否则把新节点的$nextNode设置为NULL
     * 把新节点保存到Hash表的当前位置
     * @access public
     * @param $key
     * @param $value
     */
    public function insert($key, $value) {
        $index = $this->hashfunc($key);
        //新建一个节点
        if(isset($this->buckets[$index])) {
            $newNode = new HashNode($key, $value, $this->buckets[$index]);
        } else {
            $newNode = new HashNode($key, $value, NULL);
        }
        $this->buckets[$index] = $newNode; //保存新的节点
    }

    /**
     * find 拉链法下查找数据
     * 与插入数据方法相似
     * @access public
     * @param $key
     * @return mixed
     */
    public function find($key) {
        $index = $this->hashfunc($key);
        $current = $this->buckets[$index];
        while(isset($current)) { //遍历当前链表
            if($current->key == $key) { //比较当前节点的关键字
                return $current->value; //查找成功
            }
            $current = $current->nextNode; //比较下一个节点
        }
        return NULL; //查找失败
    }
}