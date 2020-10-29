<?php
header('Content-Type: application/json');
$curl = curl_init();
$refrence = $_POST['refrence'];
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$refrence,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer sk_key",
    "Cache-Control: no-cache",
    ),
));

$response = curl_exec($curl);
curl_close($curl);
//Decode current result into readable object format
$readable = json_decode($response);
//Checking errors
if ($readable->status == false) {
    echo json_encode(array($readable->message));
}elseif($readable->status == true){
    echo json_encode(array('info' => 'Transaction validated'));
}

?>
