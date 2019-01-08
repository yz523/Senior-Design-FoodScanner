<?php
$payload = array('image' => file_get_contents('165094.jpg') );


$ch = curl_init('127.0.0.1:5000/predict');
curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => json_encode($payload)
));


$response = curl_exec($ch);
if($response === FALSE){
    die(curl_error($ch));
}
$responseData = json_decode($response, TRUE);
echo $responseData['success'];
echo $responseData['predictions'];
?>