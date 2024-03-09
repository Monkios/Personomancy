<?php
	class PersonnagePartiel extends GenericEntity {
		public $est_vivant = FALSE;
		public $est_cree = FALSE;
		public $est_complet = FALSE;
		
		public $cite_etat_id = -1;
		public $cite_etat_nom = "n.d.";
		public $croyance_id = -1;
		public $croyance_nom = "n.d.";
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
		
		// Retourne le nombre d'XP qui dépassent la limite de ce qui peut
		// être investi sur un personnage
		public function GetExceedingXP(){
			return max( 0, $this->px_totaux - CHARACTER_MAX_XP_INVESTED );
		}
		
		// Retourne le nombre d'XP restants, en-dehors de ceux qui dépassent la
		// limite de ce qui peut être investi sur un personnage
		public function GetRealCurrentXP() : int {
			return $this->px_restants - $this->GetExceedingXP();
		}
		
		public function CanAfford( $xp_cost ) : bool {
			return $xp_cost <= $this->GetRealCurrentXP();
		}
		
		public function GetStatus() : string {
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