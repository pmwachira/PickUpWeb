<?php
/**
* 
*/
class DbHandler{
	private $conn;
	
	function __construct(){
		require_once dirname(__FILE__).'/db_connect.php';

		$db=new DbConnect();
		$this->conn = $db->connect();
	}
//1
	 public function createUser($name, $email,$number) {
	 	$salt='0';
	 	$password='0';
	 	$id='0';
        $response = array();
        
        // First check if user already existed in db
        if (!$this->isUserExists($email)) {
           
            $stmt = $this->conn->prepare("INSERT INTO requestor(name, email,password,salt,phone_number,requestor_id,created_at) values(?, ?,?,?,?,?,?)");
            $stmt->bind_param("sssssss", $name, $email,$password,$salt,$number,$id,NOW());
        
            $result = $stmt->execute();
 
            $stmt->close();
 
            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                $response["error"] = false;
                $response["user"] = $this->getUserByEmail($email);
            } else {
                // Failed to create user
                $response["error"] = true;
                
                $response["message"] = "Oops! An error occurred while registering requestor";
            }
        } else {
            // User with same email already existed in the db
            $response["error"] = false;
            $response["user"] = $this->getUserByEmail($email);
        }
 
        return $response;
    }

//2
		 public function createDriver($name, $email,$number) {
	 	$salt='0';
	 	$password='0';
	 	$id='0';
	 	$car_reg='0';
	 	$model_type='0';
	 	$rating='0';
	 	$last_known_loc='0';
	 	$last_checking_time='0';

        $response = array();
        
        // First check if user already existed in db
        if (!$this->isUserExists($email)) {
           
            $stmt = $this->conn->prepare("INSERT INTO driver(name, email,password,salt,phone_number,driver_id,car_reg,model_type,rating,last_known_loc,last_checking_time,created_at) values(?, ?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("ssssssssssss", $name, $email,$password,$salt,$number,$id,$car_reg,$model_type,$rating,$last_known_loc,$last_checking_time,NOW());
        
            $result = $stmt->execute();
 
            $stmt->close();
 
            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                $response["error"] = false;
                $response["user"] = $this->getDriverByEmail($email);
            } else {
                // Failed to create user
                $response["error"] = true;
                
                $response["message"] = "Oops! An error occurred while registering requestor";
            }
        } else {
            // User with same email already existed in the db
            $response["error"] = false;
            $response["user"] = $this->getDriverByEmail($email);
        }
 
        return $response;
    }

//0
     public function userLogin($name, $email) {
	 	
        $response = array();
        
        $stmt=$this->conn->prepare("SELECT * FROM requestor WHERE name=? AND email=?");
        $stmt->bind_param("s",$name,$email);
        $stmt->execute();
        $stmt->store_result();
		$num_rows=$stmt->num_rows;
		$stmt->close();
		if() $num_rows>0){
			 $response["error"] = false;
			  $response["message"] = 'Match of user found';
		}else{
			$response["error"] = true;
			$response["message"] = 'Match of user NOT found';
		}
        return $response;
    }

//0
     public function driverLogin($name, $email) {
	 	
        $response = array();
        
        $stmt=$this->conn->prepare("SELECT * FROM driver WHERE name=? AND email=?");
        $stmt->bind_param("s",$name,$email);
        $stmt->execute();
        $stmt->store_result();
		$num_rows=$stmt->num_rows;
		$stmt->close();
		if() $num_rows>0){
			 $response["error"] = false;
			  $response["message"] = 'Match of driver found';
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

//two in one function
public function show_nearest_drivers($current_loc){
	//handle driver look up with same function
//calculate euclidean distance
//a^2+b^2=c^2
			$response=array();
			$time=NOW();
			$stmt=$this->conn->prepare("SELECT driver_id,name,email FROM driver WHERE last_known_loc= ? AND last_checking_time=?");
			$stmt->bind_param("ss",$current_loc,$time);
			if($stmt->execute()){
			$stmt->bind_result($driver_id,$name,$email);
			$stmt->fetch();
			$user=array();
			$user["driver_id"]=$user_id;
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

//0
public function init_request($name,$email,$phone_number,$start_loc,$dest_loc){
	$response=array();
	
	$stmt=$this->conn->prepare("INSERT INTO engagements (pick_id,pick_num,pick_coords,drop_coords,stats) VALUES(?,?,?,?,?)");
	$stmt->bind_param("sssss",$name,$phone_number,$start_loc,$dest_loc,'ALPHA');
	if($stmt->execute()){
	$response['error']= false;
	$response['message']= 'Request successfully Submitted';
	}else{
	$response['error']= true;
	$response['drivers']= 'Request NOT successfully Submitted';
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

//0
public function calculate_cost($eng_id){
	$stmt=$this->conn->prepare("SELECT pick_coords,drop_coords FROM engagements WHERE eng_id= ? ");
			$stmt->bind_param("s",$eng_id);
			if($stmt->execute()){
			$stmt->bind_result($pick_coords,$drop_coords);
			$stmt->fetch();
			//$cost=$pick_coords*$drop_coords;
			$cost=100000000;
			$stmt->close();
			$response['error']= false;
			$response['cost']= $cost;
		}else{
			$response['error']= true;
			$response['cost']= '000';
		}
}

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

//0
public function driver_tracking($check_time, $current_loc){
	$eng_id=0;
	$response=array();
	//fetch eng id
	$stmt=$this->conn->prepare("UPDATE engagements SET check_time=?,current_loc=? WHERE eng_id=?");
	$stmt->bind_param("sss",$check_time,$current_loc,$eng_id);
	if($stmt->execute()){
	$response['error']= false;
	$response['message']= 'Engagement current location successfully Submitted';
	}else{
	$response['error']= true;
	$response['drivers']= 'Engagement current location NOT successfully Submitted';
	}
	return $response;
}

	public function isUserExists($email){
	$stmt=$this->conn->prepare("SELECT user_id FROM users WHERE email= ?");
	$stmt->bind_param("s",$email);
	$stmt->execute();
	$stmt->store_result();
	$num_rows=$stmt->num_rows;
	$stmt->close();
	return $num_rows>0;
	}

		public function getUserByEmail($email){
		$stmt=$this->conn->prepare("SELECT user_id,name,email,created_at FROM requestor WHERE email= ?");
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
		}else{
			return NULL;	
		}
		}

		public function getDriverByEmail($email){
		$stmt=$this->conn->prepare("SELECT driver_id,name,email,created_at FROM driver WHERE email= ?");
			$stmt->bind_param("s",$email);
			if($stmt->execute()){
			$stmt->bind_result($driver_id,$name,$email,$created_at);
			$stmt->fetch();
			$user=array();
			$user["driver_id"]=$user_id;
			$user["name"]=$name;
			$user["email"]=$email;
			$user["created_at"]=$created_at;
			$stmt->close();
			return $user;
		}else{
			return NULL;	
		}
		}
}
  
?>