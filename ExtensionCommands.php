<?php
//Extension commands are run anytime a message is received and have the liberty to decide to respond or not.
//$args is 0 => Sender, 1=> message

$ExtensionCommands = [

	'quickchat' => function(&$bucket, $args) {
		$triggerWords = [
			'bitch',
			'idiot',
			'stupid',
			'dumbass'
		];

		$quickChat		= [
			'Wow!',
			'OMG!',
			'No Way!',
			'Savage!',
			'Incoming!'
		];

		foreach($triggerWords as $word) {
			print("Checking " . strtolower($args[1]) . " for " . $word . "\n");
			if(strpos(strtolower($args[1]), $word) !== false) {
				$quickChatRand = $quickChat[rand(0, count($quickChat))];
				$bucket->getSource()->say($quickChatRand);
				return;
			}
		}
	}

];
