<?php

use DiDom\Document;
use DiDom\Query;

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
			$bucket->getSource()->say("Latest News: " . $blogHeadline . "    |   Read more here: " . 'https://rltracker.pro/blogposts/' . $lastNews['lastBlog']);
			
			file_put_contents('news.txt', serialize($lastNews));	
		}

		catch(Exception $e) {
			print("Failed to get updates from blog: " . $e . "\n");
		}

	},

];
