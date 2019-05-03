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
		//Update profiles
		$this->getProfiles();

		foreach($this->profiles as $profile) {
			if($profile[0] == $username) {
				return $profile[1];
			}
		}

		return "";
	}

	// Returns 1 for $username already exists, overwritten
	// Returns 0 for success
	function setProfile($username, $steamprofile) {
		$this->getProfiles();

		foreach($this->profiles as &$profile) {
			if($profile[0] == $username) {
				$profile[1] = $steamProfile;
				return 1;
			}
		}

		$this->profiles[] = [$username, $steamprofile];
		return 0;

	}

	// Returns 0 for success
	// Returns -1 for error
	function saveProfiles() {
		Global $Configs;

		if(Count($this->profiles) > 0) {
			file_put_contents($Configs['default_profiles_file'], serialize($this->profiles));
			return 0;
		}

		return -1;
	}
}
