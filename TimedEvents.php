<?php

use DiDom\Document;
use DiDom\Query;
require_once 'Rank.php';

$TimedEvent = [

	'event'				=> function(&$bucket) {;},
	
	'Check For News'	=> function(&$bucket) {
		Global $Configs;
		$newsFile = $Configs['default_news_file'];

		//Get last news found (or none)
		if(file_exists($newsFile))
			$lastNews = unserialize(file_get_contents($newsFile));
		else
			$lastNews = ['lastBlog' => 456];

		//Check if there's something newer:
		$lastNews['lastBlog']++;
		
		try {
			$blogdoc = new Document('https://rltracker.pro/blogposts/' . $lastNews['lastBlog'], true);
			$blogHeadline = $blogdoc->find("/html/body/div[1]/div[3]/div/div/div/h4/text()", Query::TYPE_XPATH)[0];
			$blogHeadline = str_replace("\n", "", $blogHeadline);
			$bucket->getSource()->say("Latest News: " . $blogHeadline . "    |   Read more here: " . 'https://rltracker.pro/blogposts/' . $lastNews['lastBlog']);
			
			file_put_contents($newsFile, serialize($lastNews));	
		}

		catch(Exception $e) {
			print("Failed to get updates from blog: " . $e . "\n");
		}

	},

	'Check Ranks'		=> function(&$bucket) {
		Global $Configs;
		Global $Ranks;

		$ranksFile		= $Configs['default_ranks_file'];
		$profilesFile	= $Configs['default_profiles_file'];

		$profiles	= [];
		$ranks		= [];

		if(file_exists($ranksFile))
			$ranks = unserialize(file_get_contents($ranksFile));
		
		if(file_exists($profilesFile))
			$profiles = unserialize(file_get_contents($profilesFile));

		foreach($profiles as $profile) {
			$comparisonRanks = new Rank();
			$comparisonRanks->steamProfile = $profile[1];
			$comparisonRanks->getRank();

			if(key_exists($profile[0], $ranks)) {
				
				foreach($ranks[$profile[0]]->ranks as $rank => &$value) {
					if($comparisonRanks->ranks[$rank] < $value) {
						$bucket->getSource()->say("Back to the drawing board " . $profile[0] . "! Demoted in " . $rank . " to " . $Ranks[$comparisonRanks->ranks[$rank]]); 
					}
					if($comparisonRanks->ranks[$rank] > $value) {
						$bucket->getSource()->say("Good work ". $profile[0] . "! Promoted in " . $rank . " to " . $Ranks[$comparisonRanks->ranks[$rank]]);
					}
					$value = $comparisonRanks->ranks[$rank];
				}

			} else {
				$ranks[$profile[0]] = $comparisonRanks;
			}
		}

		file_put_contents($ranksFile, serialize($ranks));

	}

];
