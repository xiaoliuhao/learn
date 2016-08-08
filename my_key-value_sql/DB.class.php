<?php
/**
 * Create by PhpStorm
 * Author: Liu  <470401911@qq.com>
 * Date: 2016/8/8
 * Time: 17:18
 * Version: learn
 */
include 'config.php';
/**
 * Class DB
 */
class DB{
    private $idx_fp; //索引文件的句柄
    private $dat_fp; //数据文件的句柄
    private $closed; //记录数据库是否已经关闭

    /**
     * open 打开一个数据库
     * @access public
     * @param $pathname 数据库的名字
     * @return int
     */
    public function open($pathname) {
        $idx_path = $pathname.'.idx'; //索引文件的路径
        $dat_path = $pathname.'.dat'; //数据文件的路径
        /**
         * 索引文件存在 初始化成功
         */
        if(!file_exists($idx_path)) {
            $init = true;  //标志是否需要初始化索引文件
            $mode = "w+b"; //读写 文件不存在则创建此文件
        } else {
            $init = false;
            $mode = "r+b"; //读写 文件不存在就打开失败
        }
        //索引文件存在 , 以wb方式打开文件, 索引文件不存在,以rb方式打开
        $this->idx_fp = fopen($idx_path, $mode);
        if(!$this->idx_fp) {
            return DB_FAILURE;
        }
        //由前面的init判断是否需要初始化
        if($init){
            //把索引块写入到文件中
            $elem = pack('L', 0x00000000);
            /**
             * 把262144个数值为0的长整型数字(占4个字节)写入文件中
             * 共占用1MB空间
             * 所以没有任何数据插入到数据库的情况下,索引文件也占用1MB空间
             */
            for ($i = 0; $i < DB_BUCKET_SIZE; $i++) {
                fwrite($this->idx_fp, $elem, 4);
            }
        }
        //打开数据文件,与打开索引文件方法相同
        $this->dat_fp = fopen($dat_path,$mode);
        if(!$this->dat_fp) {
            return DB_FAILURE;
        }
        return DB_SUCCESS;
    }

    /**
     * _hash 根据给定的的字符串计算Hash值
     * 先通过MD5处理成长度为32字符的字符串 去前8个字符作为计算串
     * 利用Times33算法把他处理成一个整数并返回
     * @access public
     * @param $string
     * @return int
     */
    private function _hash($string) {
        $string = substr(md5($string), 0, 8);
        $hash = 0;
        for($i =0; $i < 8; $i++) {
            $hash += 33*$hash + ord($string{$i});
        }
        return $hash & 0x7FFFFFFF; //按位与运算
    }

    /**
     * fetch 根据指定的键查找指定的记录
     * 先通过_hash计算Hash值,然后 通过Hash值计算出记录所在的Hash链的文件偏移量
     * Hash值除以Hash桶大小再乘以4 乘以4是因为每个链表指针的大小为4字节
     *
     * 通过fseek函数把文件指针移动到目标位置,并通过fread函数读取4字节
     * 这4字节就是目标Hash链表的文件偏移量
     * @access public
     * @param $key
     * @return null|string
     */
    public function fetch($key) {
        $offset = ($this->_hash($key) % DB_BUCKET_SIZE) *4;
        fseek($this->idx_fp, $offset, SEEK_SET);
        //因为之前是以二进制形式把链表指针写入到索引文件中,所以我们需要通过unpack把它解包成数字
        $pos = unpack('L', fread($this->idx_fp, 4));
        $pos = $pos[1];
        $found = false; //标记是否找到指定记录
        while($pos) { //pos不为0 表示还没有Hash表没有遍历完毕
            fseek($this->idx_fp, $pos, SEEK_SET); //把文件指针移动到$pos位置
            $block = fread($this->idx_fp, DB_INDEX_SIZE); //fread读取一条索引记录大小的数据进行分析
            $cpkey = substr($block, 4, DB_KEY_SIZE); //当前索引记录的key
            //strncmp比较字符串 此处比较当前索引记录的key与查找的key是否相同
            if(!strncmp($key, $cpkey, strlen($key))) {
                //找到记录,读取数据
                $dataoff = unpack('L',substr($block, DB_KEY_SIZE + 4, 4));
                $dataoff = $dataoff[1]; //unpack返回一个数组 key=1

                $datalen = unpack('L', substr($block, DB_KEY_SIZE + 8, 4));
                $datalen = $datalen[1];

                $found = true;
                break;
            } else {
                //未找到记录 吧pos设置为下一条索引记录
                $pos = unpack('L', substr($block, 0, 4));
                $pos = $pos[1];
            }
        }

        if(!$found) {
            return NULL;
        }
        //使用fseek()函数把数据文件的文件指针定位到我们需要的数据记录上
        fseek($this->dat_fp, $dataoff, SEEK_SET);
        //使用fread()函数读取指定长度数据 并返回
        $data = fread($this->dat_fp, $datalen);
        return $data;
    }

    /**
     * insert 把一条记录插入到数据库中
     * @access public
     * @param $key  键值
     * @param $data 相应的数据
     * @return int
     */
    public function insert($key, $data) {
        /**计算出索引记录所在的Hash链表的文件偏移量
         *通过fast获取到索引文件和数据文件的下一个空闲空间文件偏移量$idxoset和$datoff
         */
        $offset = ($this->_hash($key) %DB_BUCKET_SIZE) *4;
        $idxoff = fstat($this->idx_fp); // 通过已打开的文件指针取得文件信息(详情参见README.md)
        $idxoff = intval($idxoff['size']);  //intval获取变量的整数值 (取整)

        $datoff = fstat($this->dat_fp);
        $datoff = intval($datoff['size']);
        //比较插入的索引记录的$key是否大于限定的最大长度
        $keylen = strlen($key);
        if($keylen > DB_KEY_SIZE) {
            return DB_FAILURE;
        }

        /**
         * 构造一个索引记录块$block
         */
        $block = pack('L', 0x00000000); //指向下一条索引记录的指针 此处填充为0 表示已经没有下一条了
        $block .= $key; //键 要插入的key
        $space = DB_KEY_SIZE - $keylen; //如果key没有达到指定的最大长度 ↓↓
        for ($i = 0; $i < $space; $i++) {
            $block .= pack('C', 0x00);  //用字符0作为填充,直到达到键的最大长度为止
        }
        $block .= pack('L', $datoff);   //数据记录所在数据文件的偏移量域填充为数据文件的下一个空闲空间的文件偏移量
        $block .= pack('L', strlen($data));//数据记录的长度域填充为要插入的数据记录的长度

        //把索引文件的文件的偏移量移动到索引记录所在的Hash链表位置上
        fseek($this->idx_fp, $offset, SEEK_SET);
        //读取Hash链表的开始索引记录的文件偏移量$pos
        $pos = unpack('L', fread($this->idx_fp, 4));
        $pos = $pos[1];
        /**
         * $pos == 0
         * 表示此时 Hash 链表为空
         * 把新的索引记录插入到索引文件的空闲位置上
         * 并修改Hash链表的开始索引记录的文件偏移量为新插入的索引记录的位置
         */
        if($pos == 0) {
            fseek($this->idx_fp, $offset, SEEK_SET);
            fwrite($this->idx_fp, pack('L', $idxoff), 4);

            fseek($this->idx_fp, 0, SEEK_END);
            fwrite($this->idx_fp, $block, DB_INDEX_SIZE);

            fseek($this->idx_fp, 0, SEEK_END);
            fwrite($this->dat_fp, $data, strlen($data));

            return DB_SUCCESS;
        } //else:
        /**
         * $pos != 0
         * 表示此时 Hash 链表不为空
         * 遍历此Hash表,查找Hash链表中是否已经存在要插入的$key
         *  - 存在: 插入失败
         *  - 不存在: 把新的缩影记录插入到索引文件的空闲位置上
         *            并把Hash链表中最后一个索引记录节点的next指针(指向下一个索引记录节点的文件偏移量)
         *            修改为新插入索引记录的位置,使其成为Hash链表中的最后一个节点
         */
        $found = false;
        while($pos) {
            fseek($this->idx_fp, $pos, SEEK_SET);
            $tmp_block = fread($this->idx_fp, DB_INDEX_SIZE);
            $cpkey = substr($tmp_block, 4, DB_KEY_SIZE);
            if(!strncmp($key, $cpkey, strlen($key))) {
                $dataoff = unpack('L', substr($tmp_block, DB_KEY_SIZE + 4, 4));
                $dataoff = $dataoff[1];
                $datalen = unpack('L', substr($tmp_block, DB_KEY_SIZE + 8, 4));
                $datalen = $datalen[1];
                $found = true;
                break;
            }

            $prev = $pos;
            $pos = unpack('L', substr($tmp_block, 0, 4));
            $pos = $pos[1];
        }

        if ($found) {
            return DB_KEY_EXISTS;
        }

        fseek($this->idx_fp, $prev, SEEK_SET);
        fwrite($this->idx_fp, pack('L',$idxoff), 4);
        fseek($this->idx_fp, 0, SEEK_END);
        fwrite($this->idx_fp, $block, DB_INDEX_SIZE);
        fseek($this->dat_fp, 0, SEEK_END);
        fwrite($this->dat_fp, $data, strlen($data));
        return DB_SUCCESS;

    }

    public function delete($key) {
        $offset = ($this->_hash($key) % DB_BUCKET_SIZE) *4;
        fseek($this->idx_fp, $offset, SEEK_SET);

        $head = unpack('L', fread($this->idx_fp, 4));
        $head = $head[1];
        $curr = $head;
        $prev = 0;
        $found = false;
        while($curr) {
            fseek($this->idx_fp, $curr, SEEK_SET);
            $block = fread($this->idx_fp, DB_INDEX_SIZE);

            $next = unpack('L', substr($block, 0, 4));
            $next = $next[1];

            $cpkey = substr($block, 4, DB_KEY_SIZE);
            if(!strncmp($key, $cpkey, strlen($key))) {
                $found = true;
                break;
            }
            $prev = $curr;
            $curr = $next;
        }

        if(!$found) {
            return DB_FAILURE;
        }

        if($prev == 0) {
            fseek($this->idx_fp, $offset, SEEK_SET);
            fwrite($this->idx_fp, pack('L', $next), 4);
        } else {
            fseek($this->idx_fp, $prev, SEEK_SET);
            fwrite($this->idx_fp, pack('L',$next), 4);
        }
        return DB_SUCCESS;
    }

    public function close() {
        if(!$this->closed) {
            fclose($this->idx_fp);
            fclose($this->dat_fp);
            $this->closed = true;
        }
    }
}