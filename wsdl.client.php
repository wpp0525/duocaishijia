<?php

$soap = new SoapClient('http://183.81.182.4:8090/nerp/services/DCService?wsdl');

$obj = $soap->getOrder( array('qqCode'=>'1215303889'));

print_r($obj);

$result	 =  $obj->getOrderReturn;



echo urldecode($result);

  print_r($soap->__getTypes());


print_r($soap->__getFunctions());
//echo $soap->show();
?>