<?php 
// echo 123;
// $_SESSION['name'] = 'liu';
// echo $_SESSION['name'];
// session_id('2');
echo "<a href=new.php>new1</a></br>";
$a = session_name();
$b = session_id();
echo "<a href=new.php?$a=".$b.">new2</a>";
var_dump($a);
var_dump($b);
?>