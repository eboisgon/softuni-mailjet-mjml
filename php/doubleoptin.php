<?php

require_once("conf.php");

require 'vendor/autoload.php';
use \Mailjet\Resources;


$salt="grain de sel";
$url ="http://pres-local/php/";


$recipient = $_POST['email'];
$hash=md5($recipient.$salt); 

//just a small encoding for the + 
$recipient_encoded=urlencode($recipient);

print("A confirmation email has been sent, please confirm your registration by clicking on it<br><br>");
 
$mj = new \Mailjet\Client($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE);
$body = [
    'FromEmail' => $sender,
    'FromName' => "Mailjet Pilot",
    'Subject' => "Please confirm your subscription",
    'Html-part' => "<h3>Dear passenger, welcome to Mailjet!</h3><br /> Please confirm your subscription by <a href=\"".$url."doubleoptin_confirm.php?email=$recipient_encoded&h=$hash\">clicking here</a> ",
    'Recipients' => [
        [
            'Email' => $recipient
        ]
    ]
];
$response = $mj->post(Resources::$Email, ['body' => $body]);
if($response->success())
	var_dump($response->getData());
else {
	var_dump($response->getData());
	print($response->getReasonPhrase());
}

?>
