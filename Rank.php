<?php

use DiDom\Document;
use DiDom\Query;

require_once 'RankList.php';

class Rank {

	var $steamProfile		= "";
	var $ircName			= "";
	var $rocketLeagueName	= "";
	var $url				= "";

	var $ranks				= [
		// GameMode => Rank
		"Duels"				=> 0,
		"Doubles"			=> 0,
		"Standard"			=> 0,
		"Solo Standard"		=> 0

	];

	function getRank() {
		global $Ranks;
		try {		
			$url			= "https://rltracker.pro/profiles/" . $this->steamProfile . "/steam";
			$document		= new Document($url, true);
			$currentSeason	= "//div[@class='row season10_div season_div']/";

			$this->rocketLeagueName = $document->find("//h4/text()", Query::TYPE_XPATH)[0];

			$duelsString					= $document->find($currentSeason . "div[1]/div[2]/text()", Query::TYPE_XPATH)[0];
			$doublesString					= $document->find($currentSeason . "div[2]/div[2]/text()", Query::TYPE_XPATH)[0];
			$standardString					= $document->find($currentSeason . "div[4]/div[2]/text()", Query::TYPE_XPATH)[0];
			$soloStandardString				= $document->find($currentSeason . "div[3]/div[2]/text()", Query::TYPE_XPATH)[0];

			foreach($Ranks as $key => $rank) {
				if($duelsString == $rank) 
					$this->ranks["Duels"] = $key;
				if($doublesString == $rank)
					$this->ranks["Doubles"] = $key;
				if($standardString == $rank)
					$this->ranks["Standard"] = $key;
				if($soloStandardString == $rank)
					$this->ranks["Solo Standard"] = $key;
			}

			$this->url = $url; // Store to return to the user 
			
			return 0;
		} catch(Exception $e) {
			return 1;
		}
	}
}
