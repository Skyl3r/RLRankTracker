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

	var $stats				= [
		"Wins"		=> 0,
		"Goals"		=> 0,
		"Shots"		=> 0,
		"Saves"		=> 0,
		"MVPs"		=> 0,
		"Assists"	=> 0
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

	function getStats() {
		Global $Ranks;
		try {
			$url			= "https://rltracker.pro/profiles/" . $this->steamProfile . "/steam";
			$document		= new Document($url, true);
			$baseStat		= "//div[@class='base_stat_col ']/";

			$this->rocketLeagueName = $document->find("//h4/text()", Query::TYPE_XPATH)[0];

			$this->stats['Wins']				= $document->find($baseStat . "div[1]/div[1]/span[1]/text()", Query::TYPE_XPATH)[0];
			$this->stats['Goals']				= $document->find($baseStat . "div[1]/div[2]/span[1]/text()", Query::TYPE_XPATH)[0];
			$this->stats['MVPs']				= $document->find($baseStat . "div[1]/div[3]/span[1]/text()", Query::TYPE_XPATH)[0];
			$this->stats['Saves']				= $document->find($baseStat . "div[1]/div[4]/span[1]/text()", Query::TYPE_XPATH)[0];
			$this->stats['Shots']				= $document->find($baseStat . "div[1]/div[5]/span[1]/text()", Query::TYPE_XPATH)[0];
			$this->stats['Assists']				= $document->find($baseStat . "div[1]/div[6]/span[1]/text()", Query::TYPE_XPATH)[0];
	
			foreach($this->stats as &$stat) {
				$stat = str_replace("\n", "", $stat);
			}

			$this->url = $url;
			return 0;
		} catch(Exception $e) {
			return 1;
		}
	}
}
