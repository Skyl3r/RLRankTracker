<?php

use DiDom\Document;
use DiDom\Query;

require_once 'RankList.php';

class Rank {


	var $steamProfile		= "";
	var $ircName			= "";
	var $rocketLeagueName	= "";
	var $calculatedProfile	= "";
	var $url				= "";

	var $ranks				= [
		// GameMode => Rank
	];

	var $stats				= [
		"Wins"		=> 0,
		"Goals"		=> 0,
		"Shots"		=> 0,
		"Saves"		=> 0,
		"MVPs"		=> 0,
		"Assists"	=> 0
	];

	function getCalculatedProfile() {
		$this->calculatedProfile = json_decode(file_get_contents("https://calculated.gg/api/player/" . $this->steamProfile));
	}

	function getRank() {
		$this->getCalculatedProfile();
		$rankData = json_decode(file_get_contents("https://calculated.gg/api/player/" . $this->calculatedProfile . "/ranks"), true);
		foreach($rankData as $gameMode => $data) {
			$this->ranks[$gameMode] = $data['rank'];
		}
		$this->url = "https://calculated.gg/players/" . $this->calculatedProfile;
	}

	function getStats() {
		$this->getCalculatedProfile();
		$statsData = json_decode(file_get_contents("https://calculated.gg/api/player/" . $this->calculatedProfile . "/play_style/all"), true);
	}
}
