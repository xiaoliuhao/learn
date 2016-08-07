<?php 
$client = new SoapClient(null, array('location'=>"http://localhost:8080/demo/lianxi/learn/soap/proxy.php",'uri'=>"http://test-uri",
	"style"=>SOAP_RPC, "use"=>SOAP_ENCODED, "trace"=>1, "exceptions"=>0));
$tr = $client->GetTime();
$addresult = $client->add(1,4);

echo $addresult;
echo '<br>';
echo $tr;