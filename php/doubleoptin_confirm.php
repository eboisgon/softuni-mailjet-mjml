<?php

require_once("conf.php");

require 'vendor/autoload.php';
use \Mailjet\Resources;


$salt="grain de sel";
$url ="http://pres-local/php/";
$id_list=1;

$recipient = $_GET['email'];
$hash=md5($recipient.$salt); 

//just checking that it's not a fake confirmation
if($hash!=$_GET['h']){
	print("Something went wrong, please try again to register<br><br>");
} else {
	print("Thanks you for confirmig your email!!!! <br><br>");
 
	$mj = new \Mailjet\Client($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE);
	$body = [
	    'Email' => $recipient,
	    'Action' => "addnoforce"
	];
	$response = $mj->post(Resources::$ContactslistManagecontact, ['id' => $id_list, 'body' => $body]);
	if($response->success())
		var_dump($response->getData());
	else {
		var_dump($response->getData());
		print($response->getReasonPhrase());
	}
}

?>
