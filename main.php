<?php

require_once 'vendor/autoload.php';
require_once 'Config.php'; 
require_once 'TimedEvents.php';
require_once 'Rank.php';
require_once 'IrcCommands.php';
require_once 'CustomCommands.php';
require_once 'ExtensionCommands.php';

use Hoa\Irc\Client;
use Hoa\Event\Bucket;


$uri	= $Configs['default_server'];
$client = new Client(new Hoa\Socket\Client($uri));


// JOIN ROCKET LEAGUE CHANNEL ON BOOT UP
$client->on('open', function(Hoa\Event\Bucket $bucket) {
	Global $Configs;
	$bucket->getSource()->join($Configs['default_username'], $Configs['default_channel']);
	return;
});


// RESPOND TO MESSAGES
$client->on('message', function(Bucket $bucket) {
	respondToMessage($bucket);
});

$client->on('private-message', function(Bucket $bucket) {
	respondToMessage($bucket);
});

$client->on('ping', function(Bucket $bucket) {
	//Ping is a good periodical check to fire off events

	Global $TimedEvent;
	
	foreach($TimedEvent as $event) {
		$event($bucket);
	}
});

function respondToMessage(&$bucket) {
	$data	= $bucket->getData();
	$message= $data['message'];
	$sender = $data['from']['nick'];
	global $IrcCommands;
	global $ExtensionCommands;

	print($sender . ": " . $message . "\n");
	
	//Process Extension Commands
	foreach($ExtensionCommands as $command) {
		$command($bucket, [$sender, substr($message, 0, strlen($message)-1)]);
	}


	//Process IRC Commands:

	//Check if message was a command
	if(substr($message, 0, 1) == ".") {
		$command = substr($message, 1, strpos($message, ' ')-1);
	
		if(strlen($command) < 1) {
			$command = substr($message, 1);
		}

		//Find if a command exists that matches this.
		if(array_key_exists($command, $IrcCommands)) {
			$args = explode(" ", $message);
			
			//Last arg for some reason has some special character tagged to the end.
			//Blame Hoa
			$argCount = count($args)-1;
			$args[$argCount] = substr($args[$argCount], 0, strlen($args[$argCount])-1);
			$args[0] = $sender;			
			
			//Verify the correct number of arguments were passed in and run or return help message
			if(count($args)-1 == $IrcCommands[$command]['required_args']) {
				$IrcCommands[$command]['function']($bucket, $args);	
			} else {
				$bucket->getSource()->say($command . ": " . $IrcCommands[$command]['help']);
			}      
		} else {
			$bucket->getSource()->say("Command " . $command . " does not exist.");
		}		
	}

}

$client->run();
