<?php
require_once('connect.php');


$sql = "SELECT * FROM `driver` WHERE 1";
$result = mysqli_query($con, $sql);

if ($row = mysqli_num_rows($result) > 0) {
    $drivercont = 0;
    $drivers['driverDetails'] = array();
    while ($row = mysqli_fetch_array($result)) {
        $values = array();
        $drivercont++;
        $values['driver_name'] = $row['driver_name'];
        $values['email'] = $row['email'];
        $values['phone_number'] = $row['phone_number'];
        $values['driver_id'] = $row['driver_id'];
        $values['car_reg'] = $row['car_reg'];
        $values['model_type'] = $row['model_type'];
        $values['driver_rating_sum'] = $row['driver_rating_sum'];
        $values['last_known_loc'] = $row['last_known_loc'];
        $values['last_checkin_time'] = $row['last_checkin_time'];
        $values['created_at'] = $row['created_at'];
        $drivercont;
        array_push($drivers['driverDetails'], $values);

    }
//    echo '<pre>'.json_encode($drivers, JSON_PRETTY_PRINT).'</pre>';
    json_encode($drivers);
}

$transquery = "SELECT * FROM `engagements` WHERE 1";
$fetresult = mysqli_query($con, $transquery);
if ($row = mysqli_num_rows($fetresult) > 0) {
    $engagementsArray['engagements'] = array();
    $engagementCOunt=0;
    while ($row = mysqli_fetch_array($fetresult)) {
        $items = array();
        $engagementCOunt++;
        $items["eng_id"] = $row["eng_id"];
        $items["drop_id"] = $row["drop_id"];
        $items["drop_num"] = $row["drop_num"];
        $items["drop_lat"] = $row["drop_lat"];
        $items["drop_long"] = $row["drop_long"];
        $items["requestor_id"] = $row["requestor_id"];
        $items["pick_num"] = $row["pick_num"];
        $items["pick_lat"] = $row["pick_lat"];
        $items["pick_long"] = $row["pick_long"];
        $items["pick_date"] = $row["pick_date"];
        $items["load_desc"] = $row["load_desc"];
        $items["driver_id"] = $row["driver_id"];
        $items["est_cost"] = $row["est_cost"];
        $items["pick_time"] = $row["pick_time"];
        $items["drop_time"] = $row["drop_time"];
        $items["est_distance"] = $row["est_distance"];
        $items["status"] = $row["status"];
        $items["closure_doc"] = $row["closure_doc"];
        $items["customer_rating"] = $row["customer_rating"];

        $engagementCOunt;
        array_push($engagementsArray["engagements"],$items);

    }
    json_encode($engagementsArray);
  // echo "<pre>".json_encode($engagementsArray,JSON_PRETTY_PRINT)."</pre>";




}

$sqllistening = "SELECT * FROM `listening_drivers` WHERE 1";
$result = mysqli_query($con, $sqllistening);

if ($row = mysqli_num_rows($result) > 0) {

    $driversListening['driverListening'] = array();
    while ($row = mysqli_fetch_array($result)) {
        $values = array();
        $values['driver_id'] = $row['driver_id'];
        $values['from_lat'] = $row['from_lat'];
        $values['from_long'] = $row['from_long'];
        $values['grace_distance'] = $row['grace_distance'];
        $values['distance_between'] = $row['distance_between'];
        $values['from_date'] = $row['from_date'];
        $values['from_time'] = $row['from_time'];
        $values['to_lat'] = $row['to_lat'];
        $values['to_long'] = $row['to_long'];
        $values['status'] = $row['status'];
        $values['type'] = $row['type'];
        $values['created_at'] = $row['created_at'];
        array_push($driversListening['driverListening'], $values);

    }
//  echo '<pre>'.json_encode($drivers, JSON_PRETTY_PRINT).'</pre>';
    json_encode($driversListening);
}




$sqltracking = "SELECT * FROM `tracking` WHERE 1";
$result = mysqli_query($con, $sqltracking);

if ($row = mysqli_num_rows($result) > 0) {

    $driversTracking['driverTracking'] = array();
    while ($row = mysqli_fetch_array($result)) {
        $values = array();
        $values['driver_id'] = $row['driver_id'];
        $values['from_lat'] = $row['from_lat'];
        $values['from_long'] = $row['from_long'];
        $values['current_lat'] = $row['current_lat'];
        $values['current_long'] = $row['current_long'];
        $values['to_lat'] = $row['to_lat'];
        $values['to_long'] = $row['to_long'];
        $values['time'] = $row['time'];
        array_push($driversTracking['driverTracking'], $values);

    }
//  echo '<pre>'.json_encode($drivers, JSON_PRETTY_PRINT).'</pre>';
    json_encode($driversTracking);
}


$sqlrequests = "SELECT * FROM `requestor` WHERE 1";
$result = mysqli_query($con, $sqlrequests);

if ($row = mysqli_num_rows($result) > 0) {

    $requests['requests'] = array();
    $requestCOunt=0;
    while ($row = mysqli_fetch_array($result)) {
        $values = array();
        $requestCOunt++;
        $values['name'] = $row['name'];
        $values['email'] = $row['email'];
        $values['phone_number'] = $row['phone_number'];
        $values['requestor_id'] = $row['requestor_id'];
       
        array_push($requests['requests'], $values);

    }
//  echo '<pre>'.json_encode($drivers, JSON_PRETTY_PRINT).'</pre>';
    json_encode($requests);
}




?>