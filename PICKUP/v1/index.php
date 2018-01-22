<?php
 
 error_reporting(-1);//ALL
 ini_set('display_errors','On');
require_once '../include/db_handler.php';

require '../libs/Slim/Slim.php';
 
\Slim\Slim::registerAutoloader();
 
$app = new Slim\Slim();
 //used//add user
$app->post('/user/add',function() use ($app){
    verifyRequiredParams(array('name','email','phone_number','password','requestor_id'));
    $name=$app->request->post('name');
    $email=$app->request->post('email');
    $phone_number=$app->request->post('phone_number');
    $password=$app->request->post('password');
    $requestor_id=$app->request->post('requestor_id');

    $db=new DbHandler();
    $response=$db->createUser($name,$email,$phone_number,$password,$requestor_id);

    echoRespnse(200,$response);
});

//used//add user cm
$app->post('/user/gcm',function() use ($app){
    verifyRequiredParams(array('name','email','regId'));
    $name=$app->request->post('name');
    $email=$app->request->post('email');
    $regId=$app->request->post('regId');
    

    $db=new DbHandler();
    $response=$db->userGcm($name,$email,$regId);

    echoRespnse(200,$response);
});

 //used//add driver
$app->post('/driver/add',function() use ($app){
    verifyRequiredParams(array('name','email','password','driver_id','phone_number','car_reg'));
    $name=$app->request->post('name');
    $email=$app->request->post('email');
    $password=$app->request->post('password');
    $driver_id=$app->request->post('driver_id');
    $phone_number=$app->request->post('phone_number');
     $car_reg=$app->request->post('car_reg');
    

    $db=new DbHandler();
    $response=$db->createDriver($name,$email,$password,$driver_id,$phone_number,$car_reg);

    echoRespnse(200,$response);
});

 //add driver cm
$app->post('/driver/gcm',function() use ($app){
    verifyRequiredParams(array('name','email','regId'));
    $name=$app->request->post('name');
    $email=$app->request->post('email');
    $regId=$app->request->post('regId');
    

    $db=new DbHandler();
    $response=$db->driverGcm($name,$email,$regId);

    echoRespnse(200,$response);
});

 //used//user login
$app->post('/user/login',function() use($app){
    verifyRequiredParams(array('email','password'));

    $email=$app->request->post('email');
    $password=$app->request->post('password');

    $db=new DbHandler();
    $response=$db->userLogin($email,$password);

    echoRespnse(200,$response);
});

 //used//driver login
$app->post('/driver/login',function() use($app){
    verifyRequiredParams(array('driver_id','password'));

    $driver_id=$app->request->post('driver_id');
    $password=$app->request->post('password');

    $db=new DbHandler();
    $response=$db->driverLogin($driver_id,$password);

    echoRespnse(200,$response);
});

//show_nearest_drivers
$app->post('/show_nearest_drivers',function() use($app){
    verifyRequiredParams(array('current_loc'));

    $current_loc=$app->request->post('current_loc');
    
    $db=new DbHandler();
    $response=$db->show_nearest_drivers($current_loc);

    echoRespnse(200,$response);
});

 //used//init_request
$app->post('/init_request',function() use($app){
   verifyRequiredParams(array('drop_id','drop_num','drop_lat','drop_long','requestor_id','pick_num','pick_lat','pick_long','load_desc','image','est_dist','est_cost','pick_time','pick_date','collector_name','collector_id'));

    $drop_id=$app->request->post('drop_id');
    $drop_num=$app->request->post('drop_num');
     $drop_lat=$app->request->post('drop_lat');
     $drop_long=$app->request->post('drop_long');
    $requestor_id=$app->request->post('requestor_id');
     $pick_num=$app->request->post('pick_num');
     $pick_lat=$app->request->post('pick_lat');
     $pick_long=$app->request->post('pick_long');
    $load_desc=$app->request->post('load_desc');
     $image=$app->request->post('image');
     $est_dist=$app->request->post('est_dist');
     $est_cost=$app->request->post('est_cost');
    $pick_time=$app->request->post('pick_time');
    $pick_date=$app->request->post('pick_date');
    $collector_name=$app->request->post('collector_name');
    $collector_id=$app->request->post('collector_id');
    
    $db=new DbHandler();
    $response=$db->init_request($drop_id,$drop_num,$drop_lat,$drop_long,$requestor_id,$pick_num,$pick_lat,$pick_long,$load_desc,$image,$est_dist,$est_cost,$pick_time,$pick_date,$collector_name,$collector_id);

    echoRespnse(200,$response);
});

//used//driver_listeneing
$app->post('/driver_listening',function() use($app){
   verifyRequiredParams(array('driver_id','from_lat','from_long','grace_distance','distance_between','from_date','from_time','to_lat','to_long','status','type'));

    $driver_id=$app->request->post('driver_id');
    $from_lat=$app->request->post('from_lat');
     $from_long=$app->request->post('from_long');
       $grace_distance=$app->request->post('grace_distance');
       $distance_between=$app->request->post('distance_between');
    $from_date=$app->request->post('from_date');
     $from_time=$app->request->post('from_time');
     $to_lat=$app->request->post('to_lat');
    $to_long=$app->request->post('to_long');
     $status=$app->request->post('status');
     $type=$app->request->post('type');
    
    $db=new DbHandler();
    $response=$db->driver_listening($driver_id,$from_lat,$from_long,$grace_distance,$distance_between,$from_date,$from_time,$to_lat,$to_long,$status,$type);

    echoRespnse(200,$response);
});

//used//stop_driver_listeneing
$app->post('/stop_driver_listening',function() use($app){
   verifyRequiredParams(array('driver_id'));

    $driver_id=$app->request->post('driver_id');

   $db=new DbHandler();
    $response=$db->stop_driver_listening($driver_id);

    echoRespnse(200,$response);
});

//used
$app->post('/get_transaction',function() use($app){
    verifyRequiredParams(array('trans_id'));

    $trans_id=$app->request->post('trans_id');
    
    
    $db=new DbHandler();
    $response=$db->get_transaction($trans_id);

    echoRespnse(200,$response);
});


//used
$app->post('/get_transaction_closing',function() use($app){
    verifyRequiredParams(array('trans_id'));

    $trans_id=$app->request->post('trans_id');
    
    
    $db=new DbHandler();
    $response=$db->get_transaction_closing($trans_id);

    echoRespnse(200,$response);
});


//used
$app->post('/driver_accepted',function() use($app){
    verifyRequiredParams(array('trans_id','driver_id'));

    $trans_id=$app->request->post('trans_id');
    $driver_id=$app->request->post('driver_id');
    
    
    $db=new DbHandler();
    $response=$db->accept_transaction($trans_id, $driver_id);

    echoRespnse(200,$response);
});

//driver_lookup
$app->post('/driver_lookup',function() use($app){
    verifyRequiredParams(array('current_loc','pick_time','dest_loc'));

    $current_loc=$app->request->post('current_loc');
    $pick_time=$app->request->post('pick_time');
    $dest_loc=$app->request->post('dest_loc');
    
    $db=new DbHandler();
    $response=$db->driver_lookup($current_loc,$pick_time,$dest_loc);

    echoRespnse(200,$response);
});

//driver_edit request
$app->post('/driver_edit_request',function() use($app){
    verifyRequiredParams(array('name','email','phone_number','start_loc','dest_loc'));

    $name=$app->request->post('name');
    $email=$app->request->post('email');
     $phone_number=$app->request->post('phone_number');
    $start_loc=$app->request->post('start_loc');
     $dest_loc=$app->request->post('dest_loc');
   
    $db=new DbHandler();
    $response=$db->driver_edit_request($name,$email,$phone_number,$start_loc,$dest_loc);

    echoRespnse(200,$response);
});

//cost calculate

$app->post('/calculate_cost',function() use($app){
    verifyRequiredParams(array('eng_id','distance','weight','nature','value'));

    $eng_id=$app->request->post('eng_id');
    $distance=$app->request->post('distance');
    $weight=$app->request->post('weight');
    $nature=$app->request->post('nature');
    $value=$app->request->post('value');
    
    $db=new DbHandler();
    $response=$db->calculate_cost($eng_id,$distance,$weight,$nature,$value);

    echoRespnse(200,$response);
});

//actual request
$app->post('/actual_request',function() use($app){
    verifyRequiredParams(array('name','email','phone_number','start_loc','dest_loc'));

    $name=$app->request->post('name');
    $email=$app->request->post('email');
    $phone_number=$app->request->post('phone_number');
    $start_loc=$app->request->post('start_loc');
    $dest_loc=$app->request->post('dest_loc');
   
    $db=new DbHandler();
    $response=$db->actual_request($name,$email,$phone_number,$start_loc,$dest_loc);

    echoRespnse(200,$response);
});

//notify_parties
//do in driver application
$app->post('/notify_parties',function() use($app){
    verifyRequiredParams(array('requestor_number','recipient_number'));

    $requestor_number=$app->request->post('requestor_number');
    $recipient_number=$app->request->post('recipient_number');
    
    $db=new DbHandler();
    $response=$db->notify_parties($requestor_number, $recipient_number);

    echoRespnse(200,$response);
});

//driver_tracking
$app->post('/driver_tracking',function() use($app){
    verifyRequiredParams(array('driver_id','from_lat','from_long','current_lat','current_long','to_lat','to_long'));

    $driver_id=$app->request->post('driver_id');
    $from_lat=$app->request->post('from_lat');
    $from_long=$app->request->post('from_long');
      $current_lat=$app->request->post('current_lat');
    $current_long=$app->request->post('current_long');
      $to_lat=$app->request->post('to_lat');
    $to_long=$app->request->post('to_long');
    
    $db=new DbHandler();
    $response=$db->driver_tracking($driver_id, $from_lat,$from_long,$current_lat,$current_long,$to_lat,$to_long);

    echoRespnse(200,$response);
});

//closure
$app->post('/closure',function() use($app){
    verifyRequiredParams(array('eng_id','arrival_time','document'));

    $eng_id=$app->request->post('eng_id');
    $arrival_time=$app->request->post('arrival_time');
     $document=$app->request->post('document');
    
    $db=new DbHandler();
    $response=$db->save_closing_document($eng_id,$arrival_time,$document);

    echoRespnse(200,$response);
});

//-used//rate_driver
$app->post('/rate_driver',function() use($app){
    verifyRequiredParams(array('driver_id','rating'));

    $driver_id=$app->request->post('driver_id');
    $rating=$app->request->post('rating');
    
    $db=new DbHandler();
    $response=$db->rate_driver($driver_id,$rating);

    echoRespnse(200,$response);
});

//-used//driver rating
$app->post('/rate_transaction',function() use($app){
    verifyRequiredParams(array('eng_id','eng_rating','comment'));

    $eng_id=$app->request->post('eng_id');
    $eng_rating=$app->request->post('eng_rating');
    $comment=$app->request->post('comment');
    $db=new DbHandler();
    $response=$db->rate_transaction($eng_id,$eng_rating,$comment);

    echoRespnse(200,$response);
});

//-used//requestor rating
$app->post('/rate_transaction_requestor',function() use($app){
    verifyRequiredParams(array('eng_id','eng_rating','comment'));

    $eng_id=$app->request->post('eng_id');
    $eng_rating=$app->request->post('eng_rating');
    $comment=$app->request->post('comment');
    $db=new DbHandler();
    $response=$db->rate_transaction_requestor($eng_id,$eng_rating,$comment);

    echoRespnse(200,$response);
});

//complete transaction

$app->post('/complete_transaction',function() use($app){
    verifyRequiredParams(array('trans_id'));

    $eng_id=$app->request->post('trans_id');
   
    
    $db=new DbHandler();
    $response=$db->complete_transaction($eng_id);

    echoRespnse(200,$response);
});




/*
//update record
$app->put('/user/:id',function($user_id) use($app){
    global $app;
    verifyRequiredParams(array('gcm_registration_id'));
    $gcm_registration_id=$app->request->put('gcm_registration_id');
    $db=new DbHandler();
    $response=$db->updateGcmID($user_id,$gcm_registration_id);

    echoRespnse(200,$response);
});
*/
/*
//fetch all records
$app->get('/chat_rooms',function(){
    $db=new DbHandler();
    $response=array();
    $result=$db->getAllChatRooms();

    $response["error"]=false;
     $response["chat_rooms"]=$result;
   
    $response["chat_rooms"]=array();

    while($chat_room=$result->fetch_assoc()){
        $tmp=array();
        $tmp["chat_room_id"]=$chat_room["chat_room_id"];
        $tmp["name"]=$chat_room["name"];
        $tmp["created_at"]=$chat_room["created_at"];
        array_push($response["chat_rooms"], $tmp);
    }
    echoRespnse(200,$response);
});
*/

/**
 * Verifying required params posted or not
 */
 //support
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }
 
    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}
 
/**
 * Validating email address
 */
  //support
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }else{
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(400, $response);
        $app->stop();
    }
}
 //support
function IsNullOrEmptyString($str){
    return (!isset($str)||trim($str)==='');
}
 
/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
  //support
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
 
    // setting response content type to json
    $app->contentType('application/json');
 
    echo json_encode($response);
}
 $app->run();
?>