<?php

require_once 'Rank.php';

$IrcCommands = [

	"getrank" => [
		'required_args' => 1,
		'help'			=> "Requires one argument [steamprofile]",
		'function'		=> function (&$bucket, &$args) {
			Global $Ranks;
			$rank = new Rank();
			$rank->steamProfile = $args[1] . "";
			$statusCode = $rank->getRank();

			if($statusCode == 0) { // Was successful
				$rankString = "Ranks for " . $rank->rocketLeagueName . ": ";
				foreach($rank->ranks as $gameType => $rankData) {
					$rankString .= $gameType . ": " . $Ranks[$rankData] . " | ";
				}
				
				$rankString .= "See more at " . $rank->url;

				$bucket->getSource()->say($rankString);
			} else {
				$bucket->getSource()->say("No ranks available for " . $steamProfile);
			}
		}	
	],

	"checknews"  => [
		'required_args' => 0,
		'help'			=> "Checks rltracker.pro for the latest RocketLeague news",
		'function'		=> function(&$bucket, $args) {
			Global $TimedEvent;
			$TimedEvent['Check For News']();
		}

	],

	"setprofile" => [
		'required_args' => 1,
		'help'			=> "Sets your steam profile for easy access later. Requires one arg [steamprofile]",
		'function'		=> function(&$bucket, &$args) {

			//Default profiles.txt will be _PROJECT_ROOT_/profiles.txt
			$profilesFile		= 'profiles.txt';  //Adjust if you want different location
			$currentProfiles	= [];

			if(file_exists($profilesFile)) {
				$fileData = file_get_contents($profilesFile);
				$currentProfiles = unserialize($fileData);

				//Check if user already exists
				$userExists = false;
				foreach($currentProfiles as &$profile) {
					if($profile[0] == $args[0]) {
						$profile[1] = $args[1];
						$userExists = true;
					}
				}
				if($userExists == false) {
					print("User did not exists\n");
					$currentProfiles[] = [$args[0], $args[1]];
				}
			} 
			
			file_put_contents($profilesFile, serialize($currentProfiles));

			$bucket->getSource()->say("Added " . $args[1] . " to profiles list");

		}
	],

	"getprofile" => [
		'required_args'	=> 1,
		'help'			=> "Gets a stored steam profile for user. Requires one arg [username]",
		'function'		=> function(&$bucket, $args) {
			$profilesFile	= "profiles.txt";

			if(file_exists($profilesFile)) {
				$fileData = file_get_contents($profilesFile);
				$currentProfiles = unserialize($fileData);
				
				foreach($currentProfiles as $profile) {
					if($profile[0] == $args[1]) {
						$bucket->getSource()->say("Steam Profile for user " . $profile[0] . ": " . $profile[1]);
						return;
					}
				}
			}

			$bucket->getSource()->say("Could not find Steam Profile for user " . $args[1]);

		}
	],
	
	'help'		=> [
		'required_args'	=> 1,
		'help'			=> "Requires one argument: function name",
		'function'		=> function(&$bucket, $args) {

			global $IrcCommands;

			if(key_exists($args[1], $IrcCommands)) {
				$bucket->getSource()->say($args[1] . ": " . $IrcCommands[$args[1]]['help']);
			} else {
				$bucket->getSource()->say($args[1] . ": not found. Use .getcommands to see available commands");
			}

		}

	],

	'getcommands'	=> [
		'required_args' => 0,
		'help'			=> 'Gets list of available commands. Requires no arguments',
		'function'		=> function(&$bucket, $args) {
			global $IrcCommands;

			$commandList = "Available commands: ";

			foreach($IrcCommands as $command => $commandData) {
				$commandList .= $command . ' ';
			}

			$bucket->getSource()->say($commandList);
		}
	]
];
