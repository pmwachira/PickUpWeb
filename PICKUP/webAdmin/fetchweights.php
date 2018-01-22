<?php
/**
 * Created by IntelliJ IDEA.
 * User: abedx
 * Date: 12/13/2016
 * Time: 06:38 PM
 */
require_once('connect.php');
$sql = "SELECT `eng_id`, `distance_weight`, `weight_weight`, `nature_weight`, `value_weight`, `item_adjusted`, 
    `slope_change`, `rating_by_driver`, `rating_by_requestor`, `time_entered` FROM `weights_map` WHERE 1";
$exec = mysqli_query($con,$sql);
$check = mysqli_num_rows($exec);
if ($check > 0) {
    $allweight['weights']=array();
    $count=1;
    while ($row=mysqli_fetch_assoc($exec)){
        $values=array();
        $values['distance_weight']=$row['distance_weight'];
        $values['weight_weight']=$row['weight_weight'];
        $values['nature_weight']=$row['nature_weight'];
        $values['value_weight']=$row['value_weight'];
        $values['rating_by_driver']=$row['rating_by_driver'];
        $values['rating_by_requestor']=$row['rating_by_requestor'];
        //$values['count']=$count++;
        array_push($allweight['weights'],$values);

    }
    echo json_encode($allweight,JSON_PRETTY_PRINT);
}

?>