<?php
	class Player {
		public $Id;
		public $FirstName;
		public $LastName;
		public $Email;
		public $Salt;
		public $IsAnimateur = FALSE;
		public $IsAdmin = FALSE;
		public $IsActive = FALSE;
		public $PasseSaison = FALSE;
		public $DateInsert;
		public $DateModify;
		public $NbCharacters = 0;
		public $UsedExperience = 0;
		public $TotalExperience = 0;
		public $LastExperienceReceived = "";
		
		public function getFullName(){
			return $this->FirstName . " " . $this->LastName;
		}
	}
?>