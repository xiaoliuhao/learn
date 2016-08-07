<?php 
include("server.php");
$soap = new SoapServer(null,array('uri'=>"http://test-uri"));
$soap->addFunction('GetTime');
$soap->setClass('member');
$soap->handle();