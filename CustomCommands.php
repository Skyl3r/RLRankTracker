<?php

//Use this file to define your own custom commands.
//

//$IrcCommands['command_name'] = [
//	'required_args'		=> 1,																					The required number of arguments
//	'help'				=> "A helpful description for .help command_name or incorrect usage",
//	'function'			=> function(&$bucket, $args) {															Function requires a reference to bucket, in order to send messages
//																												$args will be an array where 0 => sender name and 1..  => arguments
//		$bucket->getSource()->say('message');																	use bucket->getSource()->say() to send messages to channel 
//	}
//
//];


$IrcCommands['addprofile'] = [

	'required_args'		=> 2,
	'help'				=> "Adds steamprofile for a IRC user. usage: .addprofile [IRCuser] [steamprofile]",
	'function'			=> function(&$bucket, $args) {
		Global $Configs;

		$profilesFile = $Configs['default_profiles_file'];
		$profiles = [];
	
		if(file_exists($profilesFile)) {
			$profiles = unserialize(file_get_contents($profilesFile));
		}

		$profiles[] = [$args[1], $args[2]];

		file_put_contents($profilesFile, serialize($profiles));

		$bucket->getSource()->say("Added profile " . $args[1] . " with steam ID " . $args[2]);

	}
];
