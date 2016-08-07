#使用PHP实现Hash表
##Hash函数
Hash函数的作用是把任意长度的输入,通过Hash算法变成固定长度的输出,该输出就是Hash值.这种转换是一种亚索映射,也就是Hash值得空间远小于输入的空间,不同的输入可能会散列成相同的输出,而不可能从Hash值来唯一地确定输入值.

一个好的Hash函数应该满足一下条件:每个关键字都可以均匀地分不到Hash表任意一个位置,并与其它已被散列到Hash表中的关键字不发生冲突.这是Hash函数最难实现的.
##Hash算法
关键字k可能是整数或者字符串,可以按照关键字的类型设计不同的Hash算法.整数关键字的Hash算法有一下几种.
###直接取余法
直接取余法远离比较简单,直接用关键字k除以Hash表的大小m取余数,算法如下:

`h(k) = k mod m`

这种算法只需要一个求余操作,速度比较快
###乘积取整法
乘积取整法首先使用关键字k乘以一个常数A(0<A<1),并抽取kA的小数部分.然后用Hash表大小m乘以这个值,再取整数部分即可.算法如下:
`h(k) = floor(m*(kA mod 1))`

其中,kA mod 1 表示kA的小数部分,floor是取整操作

当关键字是字符串的时候,就不能使用上面的Hash算法.因为字符串是由字符组成,所以可以吧字符串所有字符的ASCII码加起来得到一个整数,然后再按照上面的Hash算法去计算即可,算法如下:
```php
function hash($key,$m) {
    $strlen = strlen($key);
    $hashval = 0;
    for($i = 0; $i < $strlen; $i++) {
        $hashval += ord($key{$i});
    }
    return $hashval % $m;
}
```
###经典Hash算法Times33
经过计算机科学家们多年的研究,创造了一些非常有效的Hash算法,比较有名的包括:ELFHash、APHash和Times33，下面是经典的Times33算法：
```c
unsigned int DJBHash(char *str) {
    unsigned int hash = 5381;
    while(*str) {
        hash += (hash << 5) + (*str++);
    }
    return (hash & 0xFFFFFFF);
}
```
Times33算法思路就是不断乘以33,其效率和随机性都非常好,广泛用于多个开源项目中,如Apache、Perl和PHP等