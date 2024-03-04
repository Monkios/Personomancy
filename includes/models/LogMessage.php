<?php
	class LogMessage {
		public $Id;
		public $Active = true;
		public $Date;
		public $Text;
		
		public $PlayerId;
		public $PlayerName;
		
		public $Quoi;
		public $Pourquoi;
		public $Combien;
		
		public $CharacterId;
		public $CharacterName;
		
		public $CharacterActive;
		public $CharacterAlive;
		public $CharacterDestroyed;
		
		public $CanBacktrack;
		
		public function GetCharacterStatus(){
			if( $this->CharacterDestroyed ){
				$character_status = "Détruit";
			} elseif( $this->CharacterAlive ){
				if( $this->CharacterActive ){
					$character_status = "Actif";
				} else {
					$character_status = "En création";
				}
			} else {
				$character_status = "Désactivé";
			}
			
			return $character_status;
		}
	}
?>