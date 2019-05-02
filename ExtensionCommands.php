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
			if(strtolower($args[1], $word) == false) {

			} else {
				$quickChatRand = $quickChat[rand(0, count($quickChat))];
				$bucket->getSource()->say($quickChatRand);
				return;
			}
		}
	}

]
