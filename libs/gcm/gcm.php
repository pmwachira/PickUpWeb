<?php
class GCM{
	function __construct(){

	}

	public function send($to,$message){
		$fields=array(
			'to'=>$to,
			'message'=>$message,
			);
		return $this->sendPushNotification($fields);

	}
	public function sendToTopic($to,$message){
		$fields=array(
			'to'=>'/topics/'.$to,
			'data'=>$message,
			);
		return $this->sendPushNotification($fields);
	}

	public function sendMultiple($registration_ids,$message){
$fields=array(
			'registration_ids'=>$registration_ids,
			'data'=>$message,
			);
		return $this->sendPushNotification($fields);
	}

	private function sendPushNotification($fields){
		include_once '../include/config.php';

		$url='https://gcm-http.googleapis.com/gcm/send';
		$headers= array(
			'Authorization: key='.GOOGLE_API_KEY,
			'Content-Type: application/json'
			);
		$ch=curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		$result=curl_exec($ch);
		if($result==false){
				die('Curl Failed'.curl_error($ch));
		}
		curl_close($ch);

		return $result;
}




}
?>