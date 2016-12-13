<?php 

/* script to send a newsletter 
*/

require_once("conf.php");
require 'vendor/autoload.php';
use \Mailjet\Resources;
$mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'));

error_reporting(E_ERROR | E_PARSE);

print("\n\nSETTING UP THE NEWSLETTER\n");

$newsletter_id=0;


// CREATE A DRAFT 
$body = [
    'Locale' => "en_US",
    'Sender' => "Emmanuel Boisgontier",
    'SenderName' => "Emmanuel Boisgontier",
    'SenderEmail' => $sender,
    'Subject' => "Greetings from Mailjet",
    'ContactsListID' => 1,
    'Title' => "Friday newsletter"
];
$response = $mj->post(Resources::$Campaigndraft, ['body' => $body]);
if($response->success()){
	// ADD THE CONTENT TO DRAFT 
	$campaigndraft = $response->getData();
	print_r($campaigndraft);
	$body = [
	    'Html-part' => "Hello <strong>world</strong>!",
	    'Text-part' => "Hello world!"
	];
	$response = $mj->post(Resources::$CampaigndraftDetailcontent, ['id' => $campaigndraft[0]['ID'], 'body' => $body]);
	
	if($response->success()){
		print_r($response->getData());
		// SEND THE CAMPAIGN
		$response = $mj->post(Resources::$CampaigndraftSend, ['id' => $campaigndraft[0]['ID']]);
		if($response->success()){
			print_r($response->getData());
			//LOOK for STATUS
			$response = $mj->get(Resources::$CampaigndraftStatus, ['id' => $campaigndraft[0]['ID']]);
			print_r($response->getData());
			// FIND THE CAMPAIGN	
			sleep(10);
			$response = $mj->get(Resources::$Campaign, ['id' => "mj.nl=".$campaigndraft[0]['ID']]);
			if($response->success())
				var_dump($response->getData());
			else {
				var_dump($response->getData());
				print($response->getReasonPhrase());
			}
		} else {
			print("ERROR : on campaigndraft send\n");
			var_dump($response->getData());
			print($response->getReasonPhrase());
		}
	} else {
		print("ERROR : on campaigndraft detailcontent\n");
		var_dump($response->getData());
		print($response->getReasonPhrase());
	}
} else {
	print("ERROR : on campaigndraft\n");
	var_dump($response->getData());
	print($response->getReasonPhrase());
}

?>
