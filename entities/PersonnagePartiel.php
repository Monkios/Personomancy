<?php
	class PersonnagePartiel extends GenericEntity {
		public $est_vivant = FALSE;
		public $est_cree = FALSE;
		public $est_complet = FALSE;
		
		public $alignement_id = -1;
		public $alignement_nom = "n.d.";
		public $faction_id = -1;
		public $faction_nom = "n.d.";
		public $prestige_id = -1;
		public $prestige_nom = "n.d.";
		public $religion_id = -1;
		public $religion_nom = "n.d.";
		public $race_id = -1;
		public $race_nom = "n.d.";
		
		public $note = "";
		public $commentaire = FALSE;
				
		public $joueur_id = -1;
		public $joueur_nom = "";
				
		public $px_restants = -1;
		public $px_totaux = -1;
		public $pc_raciales = -1;
				
		public $dernier_changement_date;
		public $dernier_changement_par;
		
		public function GetExceedingXP(){
			$exc = 0;
			if( $this->px_totaux > CHARACTER_MAX_XP_INVESTED ){
				$exc = $this->px_totaux - CHARACTER_MAX_XP_INVESTED;
			}
			return $exc;
		}
		
		public function GetRealCurrentXP(){
			$exc = $this->GetExceedingXP();
			$real_xp = $this->px_restants;
			if( $exc > 0 ){
				$real_xp = $real_xp - $exc;
			}
			return $real_xp;
		}
		
		public function GetPerteXP(){
			$invested = $this->px_totaux - $this->px_restants;
			
			if( $invested < CHARACTER_BASE_XP ){
				return $this->px_totaux;
			}
			
			return ceil( ( $invested - CHARACTER_BASE_XP ) * CHARACTER_REBUILD_PERTE_TAUX / 5 ) * 5 + CHARACTER_BASE_XP + $this->px_restants;
		}
		
		public function CanAfford( $xp_cost ){
			return $xp_cost <= $this->GetRealCurrentXP();
		}
		
		public function GetStatus(){
			if( $this->est_vivant ){
				if( $this->est_cree ){
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