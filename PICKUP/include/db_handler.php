<?php
/**
* 
*/
class DbHandler{
	private $conn;
	
	function __construct(){
		require_once dirname(__FILE__).'/db_connect.php';
		require_once dirname(__FILE__).'/../libs/gcm/gcm.php';

		$db=new DbConnect();
		$this->conn = $db->connect();
		 $this->gcm = new GCM();
	}
//1
	 public function createUser($name, $email,$number,$password,$requestor_id) {
	 	$salt='0';
        $response = array();
        $password_to_store=hash('ripemd160', $password);
        
        // First check if user already existed in db
        if (!$this->isUserExists($email)) {
           
            $stmt = $this->conn->prepare("INSERT INTO requestor(name, email,password,salt,phone_number,requestor_id) values(?,?,?,?,?,?)");
            $stmt->bind_param("ssssss", $name, $email,$password_to_store,$salt,$number,$requestor_id);
        
            $result = $stmt->execute();
 
            
 
            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                $response["error"] = false;
                $response["user"] = $this->getUserByEmail($email);
            } else {
                // Failed to create user
                $response["error"] = true; 
                $response["message"]=$stmt->error;
                //$response["message"] = "Oops! An error occurred while registering requestor";
            }
        } else {
            // User with same email already existed in the db
            $response["error"] = false;
            $response["user"] = $this->getUserByEmail($email);
        }
        $stmt->close();
 
        return $response;
    }

//2

		 public function createDriver($name,$email,$password,$driver_id,$phone_number,$car_reg){
	 	$salt='0';
	        $gcm_regid='0';
	        $password_to_store=hash('ripemd160', $password);
	 	$model_type='0';
	 	$rating='0';
	 	$last_known_loc='0';
	 	$last_checking_time='0';

        $response = array();
        
        // First check if user already existed in db
        if (!$this->isDriverExists($email)) {
           
            $stmt = $this->conn->prepare("INSERT INTO driver(driver_name, email,password,salt,phone_number,driver_id,gcm_regid,car_reg,model_type,last_known_loc,last_checkin_time) values(?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssssss", $name,$email,$password_to_store,$salt,$phone_number,$driver_id,$gcm_regid,$car_reg,$model_type,$last_known_loc,$last_checking_time);
        
            $result = $stmt->execute();
	 $response["DEBUG"]=$stmt->error;
            $stmt->close();
 
            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                $response["error"] = false;
                $response["user"] = $this->getDriverByEmail($email);
            } else {
                // Failed to create user
                $response["error"] = true;
                
                $response["message"] = "Oops! An error occurred while registering driver";
            }
        } else {
            // User with same email already existed in the db
            $response["error"] = false;
            $response["user"] = $this->getDriverByEmail($email);
        }
 
        return $response;
    }

//0
     public function userLogin($email,$password) {
	 	
        $response = array();
        
        $password_to_verify=hash('ripemd160', $password);
  
        $stmt=$this->conn->prepare("SELECT name,email,password,salt,phone_number,requestor_id,created_at FROM requestor WHERE email=? AND password=?");
        $stmt->bind_param("ss",$email,$password_to_verify);
        $stmt->execute();
        //$stmt->store_result();
        $stmt->bind_result($name, $email,$password,$salt,$phone_number,$requestor_id,$created_at);
     	$num_rows=$stmt->fetch();
    
        $send=array();
        $send["name"]=$name;
        $send["email"]= $email;
        $send["password"]=$password;
        $send["salt"]=$salt;
        $send["phone_number"]=$phone_number;
        $send["requestor_id"]=$requestor_id;
        $send["created_at"]=$created_at;
        
       
		
		$stmt->close();
		if( $num_rows>0){
			 $response["error"] = false;
			  $response["message"] = 'Match of user found';
			  $response["user"]=$send;
		}else{
			$response["error"] = true;
			$response["message"] = 'Match of user NOT found';
		}
        return $response;
    }


//0
     public function driverLogin($driver_id,$password) {
	 	
        $response = array();
        
        $password_to_verify=hash('ripemd160', $password);
        
        $stmt=$this->conn->prepare("SELECT driver_name,email,password,salt,phone_number,driver_id,created_at,driver_rating_sum,driver_rating_count FROM driver WHERE driver_id=? AND password=?");
        $stmt->bind_param("ss",$driver_id,$password_to_verify);
      
        $stmt->execute();
        //$stmt->store_result();
        $stmt->bind_result($name, $email,$password,$salt,$phone_number,$driver_id,$created_at,$driver_rating_sum,$driver_rating_count);
     	$num_rows=$stmt->fetch();
    
        $send=array();
        $send["name"]=$name;
        $send["email"]= $email;
        $send["password"]=$password;
        $send["salt"]=$salt;
        $send["phone_number"]=$phone_number;
        $send["driver_id"]=$driver_id;
        $send["created_at"]=$created_at;
        if($driver_rating_sum==0){
        $send["average_rating"]=0;
        }else{
        $send["average_rating"]=$driver_rating_sum/$driver_rating_count;
       }
		
		$stmt->close();
		if( $num_rows>0){
			 $response["error"] = false;
			  $response["message"] = 'Match of driver found';
			  $response["user"]=$send;
			  
		}else{
			$response["error"] = true;
			$response["message"] = 'Match of driver NOT found';
		}
        return $response;
    }

//3
    public function updateDriverLoc($driver_id,$lastKnownLoc){
		$response=array();
		$stmt=$this->conn->prepare("UPDATE driver SET last_known_loc = ? WHERE driver_id=?");
		$stmt->bind_param("ss",$lastKnownLoc,$driver_id);
		if($stmt->execute()){
			$response['error']=FALSE;
			$response['message']='Driver Last location updated';
		}else{
			$response['error']=TRUE;
			$response['message']='Driver Last location update Failed';
			$stmt->error();
		}
		$stmt->close();

		return $response;
	}

//3//nested selects and update function
    public function accept_transaction($trans_id, $driver_id){
		    $response=array();
		    //select user gcm
		     $stmt=$this->conn->prepare("SELECT requestor_id FROM engagements WHERE eng_id=?");
		        $stmt->bind_param("s",$trans_id);
		        $stmt->execute();
		        $stmt->bind_result($requestor_id);
		     	$num_rows=$stmt->fetch();
		     	$stmt->close();
		     	 $stmt=$this->conn->prepare("SELECT gcm_regid FROM requestor WHERE requestor_id=?");
		        $stmt->bind_param("s",$requestor_id);
		        $stmt->execute();
		        $stmt->bind_result($gcm_id);
		     	$num_rows=$stmt->fetch();
		     	$stmt->close();
		    //select driver name
		    $stmt=$this->conn->prepare("SELECT driver_name,phone_number FROM driver WHERE driver_id=?");
		        $stmt->bind_param("s",$driver_id);
		        $stmt->execute();
		        $stmt->bind_result($driver_name,$phone_number);
		     	$num_rows=$stmt->fetch();
		   $stmt->close();
		    //gcm user
		    $registration_ids = array($gcm_id);
			$message = array('messages' => $driver_name."::::".$phone_number);   
			$response['auto_redirect'] = $this->gcm->send($registration_ids,$message); 	

		
		//update db
		$stmt=$this->conn->prepare("UPDATE engagements SET driver_id = ? WHERE eng_id=?");
		$stmt->bind_param("ss",$driver_id,$trans_id);
		
		if($stmt->execute()){
			$response['error']=FALSE;
			$response['message']='Driver taken transaction';
		}else{
			$response['error']=TRUE;
			$response['message']='Driver failed to take transaction';
			$stmt->error();
		}
		$stmt->close();

		return $response;
	}

//this function updates the driver rating
 public function rate_driver($driver_id,$rating){
		$response=array();
		$stmt=$this->conn->prepare("UPDATE  `noshybak_pickup`.`driver` SET  `driver_rating` =  ? WHERE  `driver`.`driver_id` = ?;");
		$stmt->bind_param("ss",$rating,$driver_id);
		if($stmt->execute()){
			$response['error']=FALSE;
			$response['message']='Driver Rating Updated';
		}else{
			$response['error']=TRUE;
			$response['message']='Driver Rating Not Updated';
			$stmt->error();
		}
		$stmt->close();

		return $response;
	}



//this function updates the customer rating
 public function rate_transaction($eng_id,$eng_rating,$comment){
		$response=array();
		$stmt1=$this->conn->prepare("SELECT MAX(time_entered)   FROM weights_map");
		$stmt1->execute();
		$stmt1->bind_result($max_time);
		$stmt1->store_result();
      		$stmt1->fetch();
		$stmt1->close();
		
		$stmt=$this->conn->prepare("UPDATE  weights_map SET eng_id=?,rating_by_driver=?,driver_comment=? WHERE  time_entered = ?");
		$stmt->bind_param("ssss",$eng_id,$eng_rating,$comment,$max_time);
		if($stmt->execute()){
			$response['error']=FALSE;
			$response['message']='transaction driver  Rating Updated';
		}else{
			
			$response['error']=TRUE;
			$response['message']='transaction driver Rating Not Updated because'.$stmt->error;
			
		}
		$stmt->close();
		

		return $response;
	}
	
	//this function updates the customer rating
 public function rate_transaction_requestor($eng_id,$eng_rating,$comment){
		$response=array();
		$stmt1=$this->conn->prepare("SELECT est_cost,pick_num   FROM engagements WHERE eng_id=?");
		$stmt1->bind_param("s",$eng_id);
		$stmt1->execute();
		$stmt1->bind_result($cost,$pick_num);
		$stmt1->store_result();
      		$stmt1->fetch();
		$stmt1->close();
		
		$stmt=$this->conn->prepare("UPDATE  weights_map SET rating_by_requestor=?,requestor_comment=? WHERE  eng_id = ?");
		$stmt->bind_param("sss",$eng_rating,$comment,$eng_id);
		if($stmt->execute()){
			$response['error']=FALSE;
			$response['cost']=$cost;
			$response['pick_num']=$pick_num;
			$response['message']='transaction requestor Rating Updated';
		}else{
			
			$response['error']=TRUE;
			$response['message']='transaction requestor Rating Not Updated because'.$stmt->error;
			
		}
		$stmt->close();
		

		return $response;
	}
	
		//this function updates transaction to complete
 public function complete_transaction($eng_id){
		$response=array();
		$complete="COMPLETE";
		
		$stmt=$this->conn->prepare("UPDATE  engagements SET status=? WHERE  eng_id=?");
		$stmt->bind_param("ss",$complete,$eng_id);
		if($stmt->execute()){
			$response['error']=FALSE;
			$response['message']='transaction  Updated';
		}else{
			
			$response['error']=TRUE;
			$response['message']='transaction  Not Updated because'.$stmt->error;
			
		}
		
		$stmt->close();
		 //select user gcm
		     $stmt=$this->conn->prepare("SELECT requestor_id FROM engagements WHERE eng_id=?");
		        $stmt->bind_param("s",$eng_id);
		        $stmt->execute();
		        $stmt->bind_result($requestor_id);
		     	$num_rows=$stmt->fetch();
		     	$stmt->close();
		     	 $stmt=$this->conn->prepare("SELECT gcm_regid FROM requestor WHERE requestor_id=?");
		        $stmt->bind_param("s",$requestor_id);
		        $stmt->execute();
		        $stmt->bind_result($gcm_id);
		     	$num_rows=$stmt->fetch();
		     	$stmt->close();

		    //gcm user
		    $registration_ids = array($gcm_id);
			$message = array('messages' => $complete."::::".$eng_id);   
			$response['auto_redirect'] = $this->gcm->send($registration_ids,$message); 
		

		return $response;
	}


//two in one function
public function show_nearest_drivers($current_loc){
	//handle driver look up with same function
			$response=array();
			$time=NOW();
			$stmt=$this->conn->prepare("SELECT driver_id,name,email FROM driver WHERE last_known_loc= ? AND last_checking_time=?");
			$stmt->bind_param("ss",$current_loc,$time);
			if($stmt->execute()){
			$stmt->bind_result($driver_id,$name,$email);
			$stmt->fetch();
			$user=array();
			$user["driver_id"]=$driver_id;
			$user["name"]=$name;
			$user["email"]=$email;
			$stmt->close();
			$response['error']= false;
			$response['drivers']= $user;
		}else{
			$response['error']= true;
			$response['drivers']= 'No drivers were returned';
		}

	return $response;
}

public function driver_listening($driver_id,$from_lat,$from_long,$grace_distance,$distance_between,$from_date,$from_time,$to_lat,$to_long,$status,$type){
        $response=array();
        
          //check current transactions first before commiting driver to listen mode
         //change date/time to a range of dates
	$stmt=$this->conn->prepare("SELECT requestor_id,eng_id,drop_lat,drop_long,pick_lat,pick_long,est_distance FROM engagements WHERE  pick_date=? AND driver_id is NULL");
	if($stmt){
	$stmt->bind_param("s",$from_date);
	if($stmt->execute()){
	$stmt->bind_result($requestor_id,$eng_id,$drop_lat,$drop_long,$pick_lat,$pick_long,$est_distance);
	$num_rows=$stmt->fetch();
	
	$stmt->close();
	$stmt1=$this->conn->prepare("SELECT name FROM requestor WHERE requestor_id=?");
	$stmt1->bind_param("s",$requestor_id);
	
	if($stmt1->execute()){

	$stmt1->bind_result($requestor_name);
	
	$num_rows=$stmt1->fetch();
	$stmt1->close();
	}
	if($num_rows){
		if($this->checkViability($from_lat,$from_long,$to_lat,$to_long,$pick_lat,$pick_long,$drop_lat,$drop_long,$grace_distance)){
			//transaction looks viable
			$stmt=$this->conn->prepare("SELECT gcm_regid FROM driver WHERE driver_id=?");
			$stmt->bind_param("s",$driver_id);
					if($stmt->execute()){
					$stmt->bind_result($gcm_regid);
					$stmt->store_result();
      					$stmt->fetch();
					$registration_ids = array($gcm_regid);
				    	$message = array('messages' => $eng_id."::::".$requestor_name);
				    	$response['auto_redirect'] = $this->gcm->send($registration_ids, $message);
					}
					$stmt->close();
		
			}
    	
    	
	         }
         }
         
        }
	$stmt=$this->conn->prepare("INSERT INTO listening_drivers (driver_id,from_lat,from_long,grace_distance,distance_between,from_date,from_time,to_lat,to_long,status,type) VALUES(?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE from_lat=?,from_long=?,grace_distance=?,distance_between=?,from_date=?,from_time=?,to_lat=?,to_long=?,status=?,type=?");
	$stmt->bind_param("sssssssssssssssssssss",$driver_id,$from_lat,$from_long,$grace_distance,$distance_between,$from_date,$from_time,$to_lat,$to_long,$status,$type,$from_lat,$from_long,$grace_distance,$distance_between,$from_date,$from_time,$to_lat,$to_long,$status,$type);
	
	if($stmt->execute()){
	$response['error']= false;
	$response['message']= 'Driver Mode Listening';
	}else{
	$response['error']= true;
	//$response['drivers']= $stmt->error;
	$response['drivers']= 'Driver Mode failed to change';
	//}
	}
	$stmt->close();
	return $response;
}

//0
public function stop_driver_listening($driver_id){
	
	$response=array();
	
	$stmt=$this->conn->prepare("DELETE  FROM listening_drivers  WHERE driver_id=?");
	$stmt->bind_param("s",$driver_id);

	if($stmt->execute()){
	$response['error']= false;
	$response['message']= 'Driver Tracking STopped';
	}else{
	$response['error']= true;
	$response['drivers']= 'Driver Tracking NOT STopped';
	}
	return $response;
}

//0
public function init_request($drop_id,$drop_num,$drop_lat,$drop_long,$requestor_id,$pick_num,$pick_lat,$pick_long,$load_desc,$image,$est_dist,$est_cost,$pick_time,$pick_date,$collector_name,$collector_id){
	
	$stmt1=$this->conn->prepare("SELECT name FROM requestor WHERE requestor_id=?");
	$stmt1->bind_param("s",$requestor_id);
	
	if($stmt1->execute()){

	$stmt1->bind_result($requestor_name);
	
	$num_rows=$stmt1->fetch();
	$stmt1->close();
	}
         $tmp='TR'.uniqid(); //generate unique transaction ids
        $status='ALPHA';
        $drop_time='0000';
	$response=array();
	$stmt=$this->conn->prepare("INSERT INTO engagements (eng_id,drop_id,drop_lat,drop_long,requestor_id,pick_num,pick_lat,pick_long,load_desc,image,est_distance,est_cost,status,pick_time,pick_date,drop_time,collector_name,collector_id) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	$stmt->bind_param("ssssssssssssssssss",$tmp,$drop_id,$drop_lat,$drop_long,$requestor_id,$pick_num,$pick_lat,$pick_long,$load_desc,$image,$est_dist,$est_cost,$status,$pick_time,$pick_date,$drop_time,$collector_name,$collector_id);

	if($stmt->execute()){
	
	$response['error']= false;
	$response['request_id']= $tmp;
	$response['message']= 'Request successfully Submitted';
	}else{
	$response['error']= true;
	$response['drivers']= 'Request NOT successfully Submitted'.$stmt->error;
	}
	
	
	//relay request to driver if exists
//change date/time to a range of dates
	$stmt1=$this->conn->prepare("SELECT driver_id,from_lat,from_long,to_lat,to_long,type,grace_distance FROM listening_drivers WHERE from_date=?");
	$stmt1->bind_param("s",$pick_date);
	
	if($stmt1->execute()){

	$stmt1->bind_result($driver_id,$from_lat,$from_long,$to_lat,$to_long,$type,$grace_distance);
	
	$num_rows=$stmt1->fetch();
	$stmt1->close();
	if($num_rows){
		if($this->checkViability($from_lat,$from_long,$to_lat,$to_long,$pick_lat,$pick_long,$drop_lat,$drop_long,$grace_distance)){
		$stmt2=$this->conn->prepare("SELECT gcm_regid FROM driver WHERE driver_id=?");
		$stmt2->bind_param("s",$driver_id);
			if($stmt2->execute()){
				$stmt2->bind_result($reg);
				$stmt2->fetch();
				$response['check']=$tmp;
				$registration_ids = array($reg);
				$message = array('messages' => $tmp."::::".$requestor_name);
			    	$response['auto_redirect'] = $this->gcm->send($registration_ids,$message);
			    	$stmt2->close();
	}
	}else{
	$response['viabilityCheck']='Request distance exceeds preferred!';
	}
	}
	}

	return $response;
}

//0
     public function get_transaction($trans_id) {
        $response = array();
  
        $stmt=$this->conn->prepare("SELECT requestor_id,pick_lat,pick_long,drop_lat,drop_long,load_desc,est_distance,est_cost,pick_time,pick_date,pick_num FROM engagements WHERE eng_id=?");
        $stmt->bind_param("s",$trans_id);
        $stmt->execute();
        //$stmt->store_result();
        $stmt->bind_result($requestor_id,$pick_lat,$pick_long,$drop_lat,$drop_long,$load_desc,$est_distance,$cost,$time,$date,$pick_num);
     	$num_rows=$stmt->fetch();
        $stmt->close();
    $stmt1=$this->conn->prepare("SELECT phone_number FROM requestor WHERE requestor_id=?");
	$stmt1->bind_param("s",$requestor_id);
	
	if($stmt1->execute()){

	$stmt1->bind_result($drop_num);
	
	$num_rows=$stmt1->fetch();
}
	$stmt1->close();
        $trans=array();
        $trans["pick_lat"]=$pick_lat;
         $trans["pick_long"]=$pick_long;
         $trans["drop_lat"]=$drop_lat;
         $trans["drop_long"]=$drop_long;
         $trans["load_desc"]=$load_desc;
         $trans["est_distance"]=$est_distance;
         $trans["cost"]=$cost;
         $trans["time"]=$time." Hrs On ".$date;
         $trans["pick_num"]=$drop_num;
         $trans["drop_num"]=$pick_num;
        
		
		if( $num_rows>0){
			 $response["error"] = false;
			  $response["transaction"]=$trans;
		}else{
			$response["error"] = true;
			 $response["transaction"]="Error".$stmt->error;
		}
		
        return $response;
    }
    
    //0
     public function get_transaction_closing($trans_id) {
        $response = array();
  
        $stmt=$this->conn->prepare("SELECT requestor_id,driver_id,pick_long,drop_lat,drop_long,load_desc,est_distance,collector_name,collector_id,est_cost FROM engagements WHERE eng_id=?");
        $stmt->bind_param("s",$trans_id);
        $stmt->execute();
        
        $stmt->bind_result($requestor_id,$driver_id,$pick_long,$drop_lat,$drop_long,$load_desc,$est_distance,$collector_name,$collector_id,$est_cost);
        $stmt->store_result();
     	$num_rows=$stmt->fetch();
     	$stmt->close();
     	
     	$stmt1=$this->conn->prepare("SELECT name FROM requestor WHERE requestor_id=?");
	$stmt1->bind_param("s",$requestor_id);
	if($stmt1->execute()){

	$stmt1->bind_result($requestor_name);
	}
	$num_rows=$stmt1->fetch();
	$stmt1->close();
    
        $trans=array();
        $trans["requestor_id"]=$requestor_id;
         $trans["pick_long"]=$pick_long;
         $trans["drop_lat"]=$drop_lat;
         $trans["drop_long"]=$drop_long;
         $trans["load_desc"]=$load_desc;
         $trans["est_distance"]=$est_distance;
         $trans["collector_name"]=$collector_name;
         $trans["collector_id"]=$collector_id;
         $trans["requestor_name"]=$requestor_name;
      	$trans["est_cost"]=$est_cost;
		
		if( $num_rows>0){
			 $response["error"] = false;
			  $response["transaction"]=$trans;
		}else{
			$response["error"] = true;
			 $response["transaction"]="Error"/*.$stmt->error*/;
		}
        return $response;
    }
//0
public function driver_edit_request($name,$email,$phone_number,$start_loc,$dest_loc){
	$eng_id=0;
	$response=array();
	//fetch eng id
	$stmt=$this->conn->prepare("UPDATE engagements SET pick_id=?,pick_num=?,pick_coords=?,drop_coords=?,stats='BETA' WHERE eng_id=?");
	$stmt->bind_param("sssss",$name,$phone_number,$start_loc,$dest_loc,$eng_id);
	if($stmt->execute()){
	$response['error']= false;
	$response['message']= 'Request Edit successfully Submitted';
	}else{
	$response['error']= true;
	$response['drivers']= 'Request Edit NOT successfully Submitted';
	}
	return $response;
}
/*
//0
public function calculate_cost($eng_id,$distance,$weight,$nature,$value){

	$response = array();
  
        $stmt=$this->conn->prepare("SELECT distance_weight,weight_weight,nature_weight,value_weight,rating_by_driver,rating_by_requestor,max(time_entered) FROM weights_map");
        //$stmt->bind_param("s",$eng_id);
        $stmt->execute();
        $stmt->bind_result($distance_weight,$weight_weight,$nature_weight,$value_weight,$rating_by_driver,$rating_by_requestor,$max_time);
     	$num_rows=$stmt->fetch();
    
       
        $cost=($distance_weight*($distance)*35)+(($weight*10)*$weight_weight)+($nature*$nature_weight)+(($value*0.1)*$value_weight);
		
		if( $num_rows>0){
			 $response["error"] = false;
			  $response["cost"]=$cost;
		}else{
			$response["error"] = true;
			 $response["transaction"]="Error".$stmt->error;
		}
		$stmt->close();
		
	return $response;
}
*/
//0
public function actual_request($name,$email,$phone_number,$start_loc,$dest_loc){
	$eng_id=0;
	$response=array();
	//fetch eng id
	$stmt=$this->conn->prepare("UPDATE engagements SET status='ACCEPTED' WHERE eng_id=?");
	$stmt->bind_param("s",$eng_id);
	if($stmt->execute()){
	$response['error']= false;
	$response['message']= 'Request Acccepted successfully Submitted';
	}else{
	$response['error']= true;
	$response['drivers']= 'Request Acccepted NOT successfully Submitted';
	}
	return $response;
}

public function userGcm($name,$email,$regId){
	
	$response=array();
	//fetch eng id
	$stmt=$this->conn->prepare("UPDATE requestor SET gcm_regid=? WHERE name=? AND email=?");
	$stmt->bind_param("sss",$regId,$name,$email);
	if($stmt->execute()){
	$response['error']= false;
	$response['message']= 'User gcm updated';
	}else{
	$response['error']= true;
	$response['drivers']= 'User gcm NOT updated';
	}
	return $response;
}

public function driverGcm($name,$email,$regId){
	
	$response=array();
	//fetch eng id
	$stmt=$this->conn->prepare("UPDATE driver SET gcm_regid=? WHERE driver_name=? AND email=?");
	$stmt->bind_param("sss",$regId,$name,$email);
	if($stmt->execute()){
	$response['error']= false;
	$response['message']= 'Driver gcm updated';
	}else{
	$response['error']= true;
	$response['drivers']= 'Driver gcm NOT updated';
	}
	return $response;
}

//0
public function driver_tracking($driver_id, $from_lat,$from_long,$current_lat,$current_long,$to_lat,$to_long){
	$eng_id=0;
	$response=array();
	//fetch eng id
	$stmt=$this->conn->prepare("INSERT INTO tracking (driver_id,from_lat,from_long,current_lat,current_long,to_lat,to_long) VALUES(?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE from_lat=?,from_long=?,current_lat=?,current_long=?,to_lat=?,to_long=?,time=now()");
	$stmt->bind_param("sssssssssssss",$driver_id,$from_lat,$from_long,$current_lat,$current_long,$to_lat,$to_long,$from_lat,$from_long,$current_lat,$current_long,$to_lat,$to_long);

	if($stmt->execute()){
	$response['error']= false;
	$response['message']= 'Driver Tracking Submitted';
	}else{
	$response['error']= true;
	$response['drivers']= 'Driver Tracking NOT successfully Submitted';
	}
	return $response;
}

	public function isUserExists($email){
	$num_rows=0;
	$stmt=$this->conn->prepare("SELECT user_id FROM users WHERE email= ?");
	if($stmt){
	$stmt->bind_param("s",$email);
	$stmt->execute();
	$stmt->store_result();
	$num_rows=$stmt->num_rows;
	$stmt->close();
	}
	return $num_rows>0;
	}

public function isDriverExists($email){
	$stmt=$this->conn->prepare("SELECT driver_id FROM driver WHERE email= ?");
	$stmt->bind_param("s",$email);
	$stmt->execute();
	$stmt->store_result();
	$num_rows=$stmt->num_rows;
	$stmt->close();
	return $num_rows>0;
	}

public function getUserByEmail($email){
		$stmt=$this->conn->prepare("SELECT requestor_id,name,email,created_at FROM requestor WHERE email= ?");
		
			$stmt->bind_param("s",$email);
			if($stmt->execute()){
			$stmt->bind_result($user_id,$name,$email,$created_at);
			$stmt->fetch();
			$user=array();
			$user["user_id"]=$user_id;
			$user["name"]=$name;
			$user["email"]=$email;
			$user["created_at"]=$created_at;
			$stmt->close();
			return $user;
		}
	
		}

		public function getDriverByEmail($email){
		$stmt=$this->conn->prepare("SELECT driver_id,driver_name,email,created_at FROM driver WHERE email= ?");
			$stmt->bind_param("s",$email);
			if($stmt->execute()){
			$stmt->bind_result($driver_id,$name,$email,$created_at);
			$stmt->fetch();
			$user=array();
			$user["driver_id"]=$driver_id;
			$user["name"]=$name;
			$user["email"]=$email;
			$user["created_at"]=$created_at;
			$stmt->close();
			return $user;
		}else{
			return NULL;	
		}
		}
		
		//to check whether the new journey(driver_loc->pick_loc->drop_loc->driver_dest) is within extra km driver can go(+drivers_initial_journey+$grace_distance*2)
		public function checkViability($driver_from_lat,$driver_from_long,$driver_to_lat,$driver_to_long,$load_from_lat,$load_from_long,$load_to_lat,$load_to_long,$grace_distance){
		$whole_distance=$this->calculate($driver_from_lat,$driver_from_long,$load_from_lat,$load_from_long)+$this->calculate($load_from_lat,$load_from_long,$load_to_lat,$load_to_long)+$this->calculate($load_to_lat,$load_to_long,$driver_to_lat,$driver_to_long);
		$driver_distance=$this->calculate($driver_from_lat,$driver_from_long,$driver_to_lat,$driver_to_long);
		if($whole_distance<=$driver_distance+(2*$grace_distance)){
		return true;
		}else{
		return false;
		}
		}
		
		//function querries google api for distance between two points
		private function calculate($init_lat,$init_long,$dest_lat,$dest_long){
		
		$url="https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$init_lat.",".$init_long."&destinations=".$dest_lat.",".$dest_long."&mode=driving&language=pl-PL";
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_PROXYPORT,3128);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
		
		$response=curl_exec($ch);
		curl_close($ch);
		$response_a=json_decode($response,true);
		$dist=$response_a['rows'][0]['elements'][0]['distance']['text'];
		$time=$response_a['rows'][0]['elements'][0]['duration']['text'];
		
		return $dist;
	
		}
		//////--------------------------------------------------------------------------------CHANGES------------------------------------------------------------------
		function getWeights($option){
	 $stmt=$this->conn->prepare("SELECT distance_weight, weight_weight, nature_weight, value_weight, item_adjusted, slope_change, rating_by_driver, rating_by_requestor, time_entered
FROM weights_map
WHERE time_entered = ( 
SELECT MAX( time_entered ) 
FROM weights_map )");
        //$stmt->bind_param("s",$eng_id);
        $stmt->execute();
        $stmt->bind_result($distance_weight,$weight_weight,$nature_weight,$value_weight,$item_adjusted,$slope_change,$rating_by_driver,$rating_by_requestor,$max_time);
	
	$num_rows=$stmt->fetch();
	if($num_rows<1){
	$distance_weight=1;
	$weight_weight=1;
	$nature_weight=1;
	$value_weight=1;
	$item_adjusted=0;
	$slope_change=1;
	$rating_by_driver=0;
	$rating_by_requestor=0;
	
	}
	//echo "test".$distance_weight."<--something?";
	$stmt->close();
        //see what value to change
        //change factor to be added at console for weight change
       // $CHANGE_FACTOR=0.1;
        $stmt=$this->conn->prepare("SELECT  MAX(id) FROM constants");
		$stmt->execute();
		$stmt->bind_result($max_id);
		$stmt->store_result();
      		$stmt->fetch();
		$stmt->close();
       		 $stmt1=$this->conn->prepare("SELECT learning_rate FROM constants WHERE id=?");
       		 $stmt1->bind_param("s",$max_id);
		$stmt1->execute();
		$stmt1->bind_result( $CHANGE_FACTOR);
		$stmt1->store_result();
      		$stmt1->fetch();
		$stmt1->close();
        //$SLOPE_CHANGE 0 for DEC, 1 for INC
        //HANDLE "NEXT"
        if($option=="next"){
        if($item_adjusted==0){
        //adjust $distance_weight
        list($distance_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$distance_weight,$slope_change);
        $item_adjusted=1;
        }else if($item_adjusted==1){
        //adjust $weight_weight
        list($weight_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$weight_weight,$slope_change);
        $item_adjusted=2;
        }else if($item_adjusted==2){
        //adjust $nature_weight
        list($nature_weight,$slope_change)=$this->adjustWeight($CHANGE_FACTOR,$nature_weight,$slope_change);
         $item_adjusted=3;
        }else if($item_adjusted==3){
        //adjust $value_weight
         list($value_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$value_weight,$slope_change);
         $item_adjusted=4;
        }else if($item_adjusted==4){
        //adjust $distance_weight
        list($distance_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$distance_weight,$slope_change);
        $item_adjusted==1;
        }
       
        }

        //HANDLE "AGAIN"
    if($option=="again"){
    if($item_adjusted==0){
        //adjust $distance_weight
        list($distance_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$distance_weight,$slope_change);
        $item_adjusted=1;
        }else if($item_adjusted==1){
        //adjust $distance_weight again
        list($distance_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$distance_weight,$slope_change);
        $item_adjusted=1;
        }else if($item_adjusted==2){
        //adjust $weight_weight again
        list($weight_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$weight_weight,$slope_change);
         $item_adjusted=2;
        }else if($item_adjusted==3){
        //adjust $nature_weight again
         list($nature_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$nature_weight,$slope_change);
         $item_adjusted=3;
        }else if($item_adjusted==4){
        //adjust $value_weight again
        list($value_weight,$slope_change)=$this->adjustWeight($CHANGE_FACTOR,$value_weight,$slope_change);
        $item_adjusted==4;
        }
    }
        //  //HANDLE "UNDO"
	 if($option=="undo"){
	 //delete recent weight entry
	 $stmt=$this->conn->prepare("DELETE FROM weights_map WHERE time_entered=?");
	 $stmt->bind_param("s",$max_time);
	    if($stmt->execute()){
	    //do getnext change
	 if($item_adjusted==0){
        //adjust $distance_weight
        list($distance_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$distance_weight,$slope_change);
        $item_adjusted=1;
        }else if($item_adjusted==1){
        //adjust $weight_weight
        list($weight_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$weight_weight,$slope_change);
        $item_adjusted=2;
        }else if($item_adjusted==2){
        //adjust $nature_weight
        list($nature_weight,$slope_change)=$this->adjustWeight($CHANGE_FACTOR,$nature_weight,$slope_change);
         $item_adjusted=3;
        }else if($item_adjusted==3){
        //adjust $value_weight
         list($value_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$value_weight,$slope_change);
         $item_adjusted=4;
        }else if($item_adjusted==4){
        //adjust $distance_weight
        list($distance_weight,$slope_change)= $this->adjustWeight($CHANGE_FACTOR,$distance_weight,$slope_change);
        $item_adjusted==1;
        }
	 	}
	    }
	
        $this->changeWeightsDb($distance_weight,$weight_weight,$nature_weight,$value_weight,$item_adjusted,$slope_change);
        return array($distance_weight,$weight_weight,$nature_weight,$value_weight);
     	
}
 function changeWeightsDb($distance_weight,$weight_weight,$nature_weight,$value_weight,$item_adjusted,$slope_change){
 
 	$response=array();
	$stmt=$this->conn->prepare("INSERT INTO weights_map(distance_weight,weight_weight,nature_weight,value_weight,item_adjusted,slope_change) VALUES(?,?,?,?,?,?)");
	$stmt->bind_param("ssssss",$distance_weight,$weight_weight,$nature_weight,$value_weight,$item_adjusted,$slope_change);

	if($stmt->execute()){
	$response['error']= false;
	$response['message']= 'Weights Changed';
	}else{
	$response['error']= true;
	$response['drivers']= 'Weights Not Changed';
	echo $stmt->error;
	}

 }

function adjustWeight($CHANGE_FACTOR,$weight,$slope_change){

if($weight==1){
//echo "weight 1";
	//decrement
	$newWeight=$weight-$CHANGE_FACTOR;
	$slope_change=0;
}else if($weight==0+$CHANGE_FACTOR){
//echo "weight 0";
	//increment
	$newWeight=$weight+$CHANGE_FACTOR;
	$slope_change=1;
}else{
	if($slope_change==0){
	//echo "sijui tushuke";
	//decrement values
	$newWeight=$weight-$CHANGE_FACTOR;
	$slope_change=0;
	}else{
	//increment values
	//echo "sijui tupande";
	$newWeight=$weight+$CHANGE_FACTOR;
	$slope_change=1;
	}
}

return array($newWeight,$slope_change);

}

//0
public function calculate_cost($eng_id,$distance,$weight,$nature,$value){
/*
$stmt=$this->conn->prepare("SELECT rating_delta
FROM weights_map
WHERE time_entered = ( 
SELECT MAX( time_entered ) 
FROM weights_map )");
$stmt->execute();
$stmt->bind_result($rating_delta);
$stmt->close();
//Use Rating Delta field to decide action
if($rating_delta==0){
//If rating remains,change next Factor
list($distance_weight,$weight_weight,$nature_weight,$value_weight)=$this->getWeights("next");
}else if($rating_delta>0){
//If rating increases,change the same Factor
list($distance_weight,$weight_weight,$nature_weight,$value_weight)=$this->getWeights("again");
}else
//If rating decreases,undo the change,go to next FactorValue/Factor
list($distance_weight,$weight_weight,$nature_weight,$value_weight)=$this->getWeights("undo");
}
*/
$stmt0=$this->conn->prepare("SELECT MAX(weight_id) FROM weights_map");
		$stmt0->execute();
		$stmt0->bind_result($max_id);
		$stmt0->store_result();
      		$stmt0->fetch();
		$stmt0->close();
		
		$stmt=$this->conn->prepare("SELECT rating_by_driver,rating_by_requestor FROM weights_map WHERE weight_id=?");
		$stmt->bind_param("s",$max_id);
		$stmt->execute();
		$stmt->bind_result($rating_by_driver,$rating_by_requestor);
		$stmt->store_result();
      		$stmt->fetch();
		$stmt->close();
		
		$max_id0=$max_id-1;
		
		$stmt=$this->conn->prepare("SELECT rating_by_driver,rating_by_requestor FROM weights_map WHERE weight_id=?");
		$stmt->bind_param("s",$max_id0);
		$stmt->execute();
		$stmt->bind_result($rating_by_driver0,$rating_by_requestor0);
		$stmt->store_result();
      		$stmt->fetch();
		$stmt->close();
		
		//increase
		if(($rating_by_driver+$rating_by_requestor)/2>($rating_by_driver0+$rating_by_requestor0)/2){
		list($distance_weight,$weight_weight,$nature_weight,$value_weight)=$this->getWeights("again");
		}
		//reamain
		if(($rating_by_driver+$rating_by_requestor)/2==($rating_by_driver0+$rating_by_requestor0)/2){
		list($distance_weight,$weight_weight,$nature_weight,$value_weight)=$this->getWeights("next");
		}
		//drop
		if(($rating_by_driver+$rating_by_requestor)/2<($rating_by_driver0+$rating_by_requestor0)/2){
		list($distance_weight,$weight_weight,$nature_weight,$value_weight)=$this->getWeights("undo");
		}
		
	
		
list($distance_weight,$weight_weight,$nature_weight,$value_weight)=$this->getWeights("next");
	$response = array();
    	//$num_rows=$stmt->fetch();
        $cost=($distance_weight*($distance)*35)+(($weight*10)*$weight_weight)+($nature*$nature_weight)+(($value*0.1)*$value_weight);
		
		//if( $num_rows>0){
			 $response["error"] = false;
			  $response["cost"]=$cost;
		/*}else{
			$response["error"] = true;
			 $response["transaction"]="Error".$stmt->error;
		}
		*/
		
	return $response;
}
}
  
?>