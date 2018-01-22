<?php
 
 error_reporting(-1);
 ini_set('display_errors','On');
require_once '../include/db_handler.php';

require '../libs/Slim/Slim.php';
 
\Slim\Slim::registerAutoloader();
 
$app = new Slim\Slim();
 //add user
$app->post('/user_add',function() use ($app){
    verifyRequiredParams(array('name','email','phone_number'));
    $name=$app->request->post('name');
    $email=$app->request->post('email');
    $phone_number=$app->request->post('phone_number');

    $db=new DbHandler();
    $response=$db->createUser($name,$email,$phone_number);

    echoRespnse(200,$response);
});

 //add driver
$app->post('/driver_add',function() use ($app){
    verifyRequiredParams(array('name','email','phone_number'));
    $name=$app->request->post('name');
    $email=$app->request->post('email');
    $phone_number=$app->request->post('phone_number');

    $db=new DbHandler();
    $response=$db->createDriver($name,$email,$phone_number);

    echoRespnse(200,$response);
});

 //user login
$app->post('/user/login',function() use($app){
    verifyRequiredParams(array('name','email'));

    $name=$app->request->post('name');
    $email=$app->request->post('email');

    $db=new DbHandler();
    $response=$db->userLogin($name,$email);

    echoRespnse(200,$response);
});

 //driver login
$app->post('/driver/login',function() use($app){
    verifyRequiredParams(array('name','email'));

    $name=$app->request->post('name');
    $email=$app->request->post('email');

    $db=new DbHandler();
    $response=$db->driverLogin($name,$email);

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

 //init_request
$app->post('/init_request',function() use($app){
    verifyRequiredParams(array('name','email','phone_number','start_loc','dest_loc'));

    $name=$app->request->post('name');
    $email=$app->request->post('email');
     $phone_number=$app->request->post('phone_number');
    $start_loc=$app->request->post('start_loc');
     $dest_loc=$app->request->post('dest_loc');
   
    $db=new DbHandler();
    $response=$db->init_request($name,$email,$phone_number,$start_loc,$dest_loc);

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
    verifyRequiredParams(array('eng_id'));

    $eng_id=$app->request->post('eng_id');
    
    $db=new DbHandler();
    $response=$db->calculate_cost($eng_id);

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
    verifyRequiredParams(array('check_time','current_loc'));

    $check_time=$app->request->post('check_time');
    $current_loc=$app->request->post('current_loc');
    
    $db=new DbHandler();
    $response=$db->driver_tracking($check_time, $current_loc);

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

//rate_driver
$app->post('/rate_driver',function() use($app){
    verifyRequiredParams(array('driver_id','rating'));

    $driver_id=$app->request->post('driver_id');
    $rating=$app->request->post('rating');
    
    $db=new DbHandler();
    $response=$db->rate_driver($driver_id,$rating);

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

function IsNullOrEmptyString($str){
    return (!isset($str)||trim($str)==='');
}
 
/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
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