<?php
	class Personnage extends GenericEntity {
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

		public $capacites_raciales = array();
		public $voies = array();
		public $capacites = array();
		public $connaissances = array();

		public $connaissances_accessibles = array();

		public $choix_capacites = array();
		public $choix_capacites_raciales = array();
		public $choix_connaissances = array();
		public $choix_voies = array();
		
		public function GetPointsVie(){
			return 1234; //CHARACTER_BASE_HP + ( $this->constitution * CHARACTER_HP_PER_CON );
		}
		
		public function GetPointsMagie(){
			return 9876; //CHARACTER_BASE_MP + ( $this->spiritisme * CHARACTER_MP_PER_SPI );
		}

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

		public function GetPerteXP(){
			$invested = $this->px_totaux - $this->px_restants;

			if( $invested < CHARACTER_BASE_XP ){
				return $this->px_totaux;
			}

			return 0; //TODO ceil( ( $invested - CHARACTER_BASE_XP ) * CHARACTER_REBUILD_PERTE_TAUX / 5 ) * 5 + CHARACTER_BASE_XP + $this->px_restants;
		}

		public function GetNextVoieCost(){
			return $this->GetVoieCost( count( $this->voies ) );
		}

		public function GetRefundVoieCost(){
			return $this->GetVoieCost( count( $this->voies ) - 1 );
		}

		public function GetVoieCost( $n ){
			return $n * CHARACTER_COST_VOIE_MULTIPLIER;
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