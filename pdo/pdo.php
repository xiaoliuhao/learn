<?php
 
try{
	$dsn = "mysql:host=localhost;dbname=bookshop";//配置pdo的数据源
	$db  = new PDO($dsn, 'root', '');
	//设置异常可捕获
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$db->exec("SET NAMES 'UTF8'");
	$sql = "INSERT INTO user(uid,password,myphoto,date) values('user002','123456','234','".date('Y-m-d H:i:s',time())."')";
	$db->exec($sql);
	//使用预处理语句
	$insert = $db->prepare("INSERT INTO user(uid,password,myphoto,date) values(?,?,?,now())");
	
	$insert->execute(array('user0044','22222','232'));
	
	$insert->execute(array('user001121','11111','1111',8,9));
	//异常
	$sql = "SELECT * FROM user";
	$query = $db->prepare($sql);
	$query->execute();
	var_dump($query->fetchAll(PDO::FETCH_ASSOC));
}catch(PDOException $err){
	echo $err->getMessage();
	var_dump($err);
}