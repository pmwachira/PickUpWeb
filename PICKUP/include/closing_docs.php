<?php

 if($_SERVER['REQUEST_METHOD']=='POST'){
 
 $doc= $_POST['doc'];
 $name = $_POST['name'];
 
 require_once dirname(__FILE__).'/db_connect.php';
 
 $path = "closing_docs/".$name.".pdf";
 

	 if(file_put_contents($path,base64_decode("$doc"))){
		 echo "Successfully Uploaded";
	 }else{
		 //echo "Error tu";
		 echo $doc;
	 }
 }else{
 echo "INVALID ACTION";
 }
 ?>