<?php 
class person{
	private $name;
	private $sex;

	public function __construct($name,$sex){
		$this->name = $name;
		$this->sex = $sex;
	}

	public function __set($key,$value){
		// echo "Setting $key to $value";
		//可以对私有属性进行设置
		$this->$key = $value;
	}

	public function __get($key){
		if(!isset($this->$key)){
			echo 'unset';
			$this->$name = 'defult';
		}
		return $this->$key;
	}

}

$student = new person('Liu','man');
$a = $student->name;
echo $a;