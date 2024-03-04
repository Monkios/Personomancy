<?php
	class Personnage extends PersonnagePartiel {
		public $alerte = -1;
		public $constitution = -1;
		public $intelligence = -1;
		public $spiritisme = -1;
		public $vigueur = -1;
		public $volonte = -1;
		
		public $capacites_raciales = array();
		public $pouvoirs = array();
		
		public $voies = array();
		public $capacites = array();
		
		public $connaissances = array();
		public $connaissances_base = array();
		public $connaissances_avancees = array();
		public $connaissances_synergiques = array();
		public $connaissances_maitres = array();
		public $connaissances_legendaires = array();
		
		public $sorts = array();
		public $recettes = array();
		
		public $choix_capacites = array();
		public $choix_connaissances = array();
		public $choix_pouvoirs = array();
		
		public $connaissances_accessibles = array();
		
		public function GetPointsVie(){
			return CHARACTER_BASE_HP + ( $this->constitution * CHARACTER_HP_PER_CON );
		}
		
		private function GetBonusPV(){
			return ( in_array( CHARACTER_BONUS_HP_VIG_AVC_ID, $this->connaissances_avancees ) ? CHARACTER_BONUS_HP_VIG_AVC_NB : 0 );
		}
		
		public function GetPointsVieStr(){
			$str = $this->GetPointsVie() + $this->GetBonusPV();
			if( $this->GetBonusPV() != 0 ){
				$str .= " (" . $this->GetPointsVie() .
						" + " . $this->GetBonusPV() . ")";
			}
			return $str;
		}
		
		public function GetPointsMagie(){
			return CHARACTER_BASE_MP + ( $this->spiritisme * CHARACTER_MP_PER_SPI );
		}
		
		private function GetBonusPM(){
			return (
					( in_array( CHARACTER_BONUS_MP_VOL_AVC_ID, $this->connaissances_avancees ) ? CHARACTER_BONUS_MP_VOL_AVC_NB : 0 ) +
					( in_array( CHARACTER_BONUS_MP_INT_AVC_ID, $this->connaissances_avancees ) ? CHARACTER_BONUS_MP_INT_AVC_NB : 0 ) +
					( in_array( CHARACTER_BONUS_MP_INT_MTR_ID, $this->connaissances_maitres ) ? CHARACTER_BONUS_MP_INT_MTR_NB : 0 ) +
					( in_array( CHARACTER_BONUS_MP_INT_LGD_ID, $this->connaissances_legendaires ) ? CHARACTER_BONUS_MP_INT_LGD_NB : 0 )
			);
		}
		
		public function GetPointsMagieStr(){
			$str = $this->GetPointsMagie() + $this->GetBonusPM();
			if( $this->GetBonusPM() != 0 ){
				$str .= " (" . $this->GetPointsMagie() .
						" + " . $this->GetBonusPM() . ")";
			}
			return $str;
		}
		
		public function GetSauvegardeAlerte(){
			return $this->alerte;
		}
		
		public function GetSauvegardeVigueur(){
			return $this->vigueur;
		}
		
		private function GetBonusVigueur(){
			return ( in_array( CHARACTER_BONUS_VIG_CON_AVC_ID, $this->connaissances_avancees ) ? CHARACTER_BONUS_VIG_CON_AVC_NB : 0 ) +
					( in_array( CHARACTER_BONUS_VIG_VOL_MTR_ID, $this->connaissances_maitres ) ? CHARACTER_BONUS_VIG_VOL_MTR_NB : 0 );
		}
		
		public function GetSauvegardeVigueurStr(){
			$str = $this->GetSauvegardeVigueur() + $this->GetBonusVigueur();
			if( $this->GetBonusVigueur() != 0 ){
				$str .= " (" . $this->GetSauvegardeVigueur() .
						" + " . $this->GetBonusVigueur() . ")";
			}
			return $str;
		}
		
		public function GetSauvegardeVolonte(){
			return $this->volonte;
		}
		
		private function GetBonusVolonte(){
			return ( in_array( CHARACTER_BONUS_VOL_SPI_AVC_ID, $this->connaissances_avancees ) ? CHARACTER_BONUS_VOL_SPI_AVC_NB : 0 ) +
					( in_array( CHARACTER_BONUS_VOL_VIG_MTR_ID, $this->connaissances_maitres ) ? CHARACTER_BONUS_VOL_VIG_MTR_NB : 0 );
		}
		
		public function GetSauvegardeVolonteStr(){
			$str = $this->GetSauvegardeVolonte() + $this->GetBonusVolonte();
			if( $this->GetBonusVolonte() != 0 ){
				$str .= " (" . $this->GetSauvegardeVolonte() .
						" + " . $this->GetBonusVolonte() . ")";
			}
			return $str;
		}
		
		public function GetForceMagique(){ return $this->intelligence; }
	}
?>