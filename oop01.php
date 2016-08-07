<?php 
class person{
	public $name;
	public $sex;

	function say(){
		echo $this->name." is ".$this->sex.'<br>';
	}
}

class family{
	public $people;
	public $loaction;

	public function __construct($people,$location){
		$this->people = $people;
		$this->location = $location;
	}
}

	$student = new person();
	$student->name="Liu";
	$student->sex="man";
	$student->say();

	echo '<pre>';
	$liu = new family($student,'ChongQing');
	echo serialize($student).'<br>';
	echo serialize($liu).'<br>';

	print_r($liu);
	// $student->say();
	// print_r($student);
	// echo 'print_r(expression)'
	// print_r((array)$student);
	// var_dump($student);

	// $str = serialize($student);
	// echo $str;

	// $stu = unserialize($str);
	// $stu->say();
	// $student_arr = array('name'=>'Liu','sex'=>'man');
 //    echo '<br>'.serialize($student_arr);


 ?>