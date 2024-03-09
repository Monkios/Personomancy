<?php
	class Personnage extends PersonnagePartiel {
		public $capacites_raciales = array();
		public $voies = array();
		public $capacites = array();
		public $connaissances = array();
		public $choix_capacites = array();
		
		public function GetPointsVie(){
			return 1234; //CHARACTER_BASE_HP + ( $this->constitution * CHARACTER_HP_PER_CON );
		}
		
		public function GetPointsMagie(){
			return 9876; //CHARACTER_BASE_MP + ( $this->spiritisme * CHARACTER_MP_PER_SPI );
		}
	}
?>