<?php 
function GetTime(){
	return date('Y-m-d',time());
}

class member{
	public function add($x,$y){
		return $x+$y;
	}
}