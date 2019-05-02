<?php

use DiDom\Document;
use DiDom\Query;
require_once 'Rank.php'

$TimedEvent = [

	'event'				=> function(&$bucket) {;},
	
	'Check For News'	=> function(&$bucket) {
		
		//Get last news found (or none)
		if(file_exists('news.txt'))
			$lastNews = unserialize(file_get_contents('news.txt'));
		else
			$lastNews = ['lastBlog' => 456];

		//Check if there's something newer:
		$lastNews['lastBlog']++;
		
		try {
			$blogdoc = new Document('https://rltracker.pro/blogposts/' . $lastNews['lastBlog'], true);
			$blogHeadline = $blogdoc->find("/html/body/div[1]/div[3]/div/div/div/h4/text()", Query::TYPE_XPATH)[0];
			$blogHeadline = str_replace("\n", "", $blogHeadline);
			$bucket->getSource()->say("Latest News: " . $blogHeadline . "    |   Read more here: " . 'https://rltracker.pro/blogposts/' . $lastNews['lastBlog']);
			
			file_put_contents('news.txt', serialize($lastNews));	
		}

		catch(Exception $e) {
			print("Failed to get updates from blog: " . $e . "\n");
		}

	},

	'Check Ranks'		=> function(&$bucket) {
		Global $Ranks;

		$profiles	= [];
		$ranks		= [];

		if(file_exists('ranks.txt'))
			$ranks = unserialize(file_get_contents('ranks.txt'));
		
		if(file_exists('profiles.txt'))
			$profiles = unserialize(file_get_contents('profiles.txt'));

		foreach($profiles as $profile) {
			$comparisonRanks = new Rank();
			$comparisonRanks->rocketLeagueName = $profile;
			$comparisonRanks->getRank();

			if(key_exists($profile[0], $ranks)) {
				
				foreach($ranks[$profile[0]]->ranks as $rank => &$value) {
					if($comparisonRanks[$rank] < $value) {
						$bucket->getSource()->say("Back to the drawing board " . $profile[0] . "! Demoted in " . $rank . " to " . $Ranks[$comparisonRanks[$rank]]); 
					}
					if($comparisonRanks[$rank] > $value) {
						$bucket->getSource()->say("Good work ". $profile[0] . "! Promoted in " . $rank . " to " . $Ranks[$comparisonRanks[$rank]]);
					}
					$value = $comparisonRanks[$rank];
				}

			} else {
				$ranks[$profile[0]] = $comparisonRanks;
			}
		}

		file_put_contents('ranks.txt', serialize($ranks));

	}

];
