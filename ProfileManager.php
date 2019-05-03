<?php

Class ProfileManager {

	var $profiles = [];

	function getProfiles() {
		Global $Configs;

		if(file_exists($Configs['default_profiles_file'])) {
			$this->profiles = unserialize(file_get_contents($Configs['default_profiles_file']));
		}
		
		return $this->profiles;
	}

	function getProfile($username) {
		print("Searching for " . $username . "\n");
		//Update profiles
		$this->getProfiles();

		foreach($this->profiles as $profile) {
			if($profile[0] == $username) {
				return $profile[1];
			}
		}

		return "";
	}
}
