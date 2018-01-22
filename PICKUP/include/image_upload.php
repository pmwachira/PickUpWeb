<?php

 if($_SERVER['REQUEST_METHOD']=='POST'){
 
 $image = $_POST['image'];
 $name = $_POST['name'];
 
 require_once dirname(__FILE__).'/db_connect.php';
 
 $path = "engagement_uploads/".$name.".jpg";
 

	 if(file_put_contents($path,base64_decode("$image"))){
		 echo "Successfully Uploaded";
	 }else{
		 echo "Error";/*Error checking on client side*/
		
	 }
 }else{
 echo "INVALID ACTION";
 }
 ?>