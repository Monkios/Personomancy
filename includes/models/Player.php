<?php
	class Player {
		public $Id;
		public $FirstName;
		public $LastName;
		public $Email;
		public $Salt;
		public $IsAnimateur = FALSE;
		public $IsAdministrateur = FALSE;
		public $IsActive = FALSE;
		public $DateInsert;
		public $DateModify;
		
		public function getFullName(){
			return $this->FirstName . " " . $this->LastName;
		}
	}
?>