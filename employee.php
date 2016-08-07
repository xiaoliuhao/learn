<?php 
interface employee{
	public function working();	
}

class teacher implements employee{
	public function working(){
		echo 'teaching...'.PHP_EOL;
	}
}

class coder implements employee{
	public function working(){
		echo 'coding...'.PHP_EOL;
	}
}

class workA{
	public function work(){
		$teacher = new teacher;
		$teacher->working();
	}
}

class workB{
	private $e;
	public function __set($key,$value){
		$this->$key = $value;
	}
	// public function set(employee $e){
	// 	$this->e = $e;
	// }

	public function work(){
		$this->e->working();
	}
}

$worka = new workA;
$worka->work();

// $workb = new workB;
// $workb->set(new coder());
// $workb->work();

$workc = new workB;
$workc->e = new teacher();
$workc->work();