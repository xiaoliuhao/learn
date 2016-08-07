<?php
define('SORT_NUMBERIC','1');
function myHash($key){
    $md5 = substr(md5($key), 0, 8);
    $seed = 31;
    $hash = 0;

    for($i = 0; $i < 8; $i++){
        $hash = $hash*$seed + ord($md5{$i});
        $i++;
    }

    return $hash & 0x7FFFFFFF;
}

class FlexiHash{
    private $serverList = array();
    private $isSorted = FALSE;

    public function __get($key){
        return $this->$key;
    }

    function addServer($server){
        //首先通过mHash函数计算出服务器的Hash的值
        $hash = myHash($server);
        //通过Hash值定位服务器列表上的某个位置
        if(!isset($this->serverList[$hash])){
            $this->serverList[$hash] = $server;
        }
        //服务器列表发生了变化,所以应该把排序标识$isSorted设置为FALSE
        $this->isSorted = FALSE;
        return TRUE;
    }

    function removeServer($server){
        //首先通过mHash函数计算出服务器的Hash值,以此Hash值作为要删除服务器的索引
        $hash = myHash($server);
    
        if(isset($this->serverList[$hash])){
            //把此服务器从服务器列表中删除
            unset($this->serverList[$hash]);
        }
        //服务器列表发生了变化,所以isSorted的值设为FALSE
        $this->isSorted = FALSE;
        return TRUE;
    }

    function lookup($key){
        $hash = myHash($key);

        if(!$this->isSorted){
            krsort($this->serverList);
            $this->isSorted = TRUE;
        }

        foreach($this->serverList as $pos => $server){
            if($hash >= $pos) return $server;
        }

        return $this->serverList[count($this->serverList) - 1];
    }
}

$hserver = new FlexiHash();

$hserver->addServer("192.168.1.1");
$hserver->addServer("192.168.1.2");
$hserver->addServer("192.168.1.3");
$hserver->addServer("192.168.1.4");
$hserver->addServer("192.168.1.5");

echo "save key1 in server :",$hserver->lookup('key1');
echo "<br>";
echo "save key2 in server :",$hserver->lookup('key2');
echo "<br>";
echo "================================".'<br>';
/**
 * test
 */
//$list_arr = $hserver->serverList;
//echo '<pre>';
//print_r($list_arr);

$hserver->removeServer("192.168.1.4");
echo "save key1 in server : ",$hserver->lookup("key1");
echo "<br>";
echo "save key2 in server : ",$hserver->lookup('key2');
echo "<br>";
echo "================================";
echo "<br>";
/**
 * test
 */

$hserver->addServer('192.168.1.6');
echo "save key1 in server : ",$hserver->lookup("key1");
echo "<br>";
echo "save key2 in server : ",$hserver->lookup('key2');
echo "<br>";
echo "================================";
echo "<br>";


echo phpversion();
//$list_arr2 = $hserver->serverList;
//echo '<pre>';
//print_r($list_arr2);


// function create_a(){
//     return 'a';
// }
// class test{
//     private $str;
//     public function __set($key,$value){
//         $this->$key = $value;
//     }

//     public function __get($key){
//         return $this->$key;
//     }

//     public function get_a(){
//         $this->str = create_a();
//     }

// }

// $t = new test();
// $t->get_a();

// $str = $t->str;
// echo $str;