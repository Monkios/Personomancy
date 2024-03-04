<?php
	class Sort extends GenericEntity {
		public $mot_pouvoir = "";
		public $cercle = 0;
				
		public $sphere_id = 0;
		public $sphere_nom = "";
				
		public $champ_id = 0;
		public $champ_nom = "";
				
		public $duree_id = 0;
		public $duree_nom = "";
				
		public $portee_id = 0;
		public $portee_nom = "";
				
		public $sauvegarde_id = 0;
		public $sauvegarde_nom = "";
		
		public function GetCompleteName(){
			return "C" . $this->cercle . " " . $this->nom;
		}
	}
?>