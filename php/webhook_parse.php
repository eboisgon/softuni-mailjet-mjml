<?php

require_once("conf.php");

require 'vendor/autoload.php';
use \Mailjet\Resources;

$reply_to="reply@example.com";

// RECOVERING THE PAYLOAD
$input = file_get_contents('php://input');
$object = json_decode($input,true);
$input_dump = print_r($object, TRUE);

//LOGIN IN FILE 
$fp = fopen('request.log', 'a');
fwrite($fp, $input_dump);
fwrite($fp, "SENDER ".$object['Sender']."\n");
fwrite($fp, "Text-part ".$object['Text-part']."\n");

$clean_tags=strip_tags($object['Html-part']);
fwrite($fp, "Html-part ".$clean_tags."\n");

//WHAT TO ANSWER ?
if (preg_match("/ping/i", substr($clean_tags,0,10))){
	$html_part="<H1>PONG</H1>";
} else {
	$html_part="<H1>OUT</H1>";
}

//SENDING A RESPONSE
$mj = new \Mailjet\Client($MJ_APIKEY_PUBLIC, $MJ_APIKEY_PRIVATE);
$body = [
    'FromEmail' => $sender,
    'FromName' => "Mailjet Pilot",
    'Subject' => "Table tennis!!!",
    'Html-part' => $html_part,
    'Recipients' => [['Email' => $object['Sender']]],
    'Mj-EventPayLoad' => "Eticket,1234,row,15,seat,B",
    'Headers' => [
        'Reply-To' => $reply_to,
	'References' => $object['Headers']['Message-ID'],
	'Original-Message-ID' => $object['Headers']['Message-ID']
    ]
];

//LOGING THE RESPONSE
fwrite($fp, print_r($body, TRUE));

$response = $mj->post(Resources::$Email, ['body' => $body]);
$response->success() && var_dump($response->getData());

fclose($fp);


