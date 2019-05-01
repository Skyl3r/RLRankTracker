<?php

use DiDom\Document;
use DiDom\Query;

class Rank {

	var $steamProfile		= "";
	var $ircName			= "";
	var $rocketLeagueName	= "";
	var $url				= "";

	var $ranks				= [
		// GameMode => Rank
		"Duels"				=> "",
		"Doubles"			=> 0,
		"Standard"			=> 0,
		"Solo Standard"		=> 0

	];

	function getRank() {
		try {		
			$url			= "https://rltracker.pro/profiles/" . $this->steamProfile . "/steam";
			$document		= new Document($url, true);
			$currentSeason	= "//div[@class='row season10_div season_div']/";

			print $url . "\n";
			$this->rocketLeagueName = $document->find("//h4/text()", Query::TYPE_XPATH)[0];

			$this->ranks["Duels"]			= $document->find($currentSeason . "div[1]/div[2]/text()", Query::TYPE_XPATH)[0];
			$this->ranks["Doubles"]			= $document->find($currentSeason . "div[2]/div[2]/text()", Query::TYPE_XPATH)[0];
			$this->ranks["Standard"]		= $document->find($currentSeason . "div[3]/div[2]/text()", Query::TYPE_XPATH)[0];
			$this->ranks["Solo Standard"]	= $document->find($currentSeason . "div[4]/div[2]/text()", Query::TYPE_XPATH)[0];

			$this->url = $url; // Store to return to the user 
			
			return 0;
		} catch(Exception $e) {
			return 1;
		}
	}
}
