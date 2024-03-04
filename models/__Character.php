<?php
	class Character extends Character_Identification {
		public $AlignementNom = "n.d.";
		public $ReligionNom = "n.d.";
		public $RaceNom = "n.d.";
		
		public $Constitution = -1;
		public $Spiritisme = -1;
		public $Vigueur = -1;
		public $Volonte = -1;
		public $Intelligence = -1;
		public $Sagesse = -1;
		
		public $CapacitesRaciales = array();
		public $Pouvoirs = array();
		
		public $Voies = array();
		public $CapacitesSelections = array();
		
		public $Connaissances = array();
		public $ConnaissancesBase = array();
		public $ConnaissancesAvancees = array();
		public $ConnaissancesSynergiques = array();
		public $ConnaissancesMaitre = array();
		public $ConnaissancesLegendaires = array();
		
		public $SortsParSphere = array();
		public $RecettesAlchimiques = array();
		
		public $ConnaissancesAccessibles = array();
		
		public function GetPointsVie(){
			return CHARACTER_BASE_HP + ( $this->Constitution * CHARACTER_HP_PER_CON );
		}
		
		private function GetBonusPV(){
			return ( in_array( CHARACTER_HP_CONN_AVC_ID, $this->ConnaissancesAvancees ) ? CHARACTER_HP_CONN_AVC_BONUS : 0 );
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
			return CHARACTER_BASE_MP + ( $this->Spiritisme * CHARACTER_MP_PER_SPI );
		}
		
		private function GetBonusPM(){
			return ( in_array( CHARACTER_MP_CONN_AVC_ID, $this->ConnaissancesAvancees ) ? CHARACTER_MP_CONN_AVC_BONUS : 0 );
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
			return ( array_key_exists( CHARACTER_ALRT_ID, $this->CapacitesSelections ) ? $this->CapacitesSelections[ CHARACTER_ALRT_ID ] : 0 );
		}
		
		public function GetSauvegardeVigueur(){
			return $this->Vigueur;
		}
		
		private function GetBonusVigueur(){
			return ( in_array( CHARACTER_VIG_CONN_AVC_ID, $this->ConnaissancesAvancees ) ? CHARACTER_VIG_CONN_AVC_BONUS : 0 ) +
					( in_array( CHARACTER_VIG_CONN_MTR_ID, $this->ConnaissancesMaitre ) ? CHARACTER_VIG_CONN_MTR_BONUS : 0 );
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
			return $this->Volonte;
		}
		
		private function GetBonusVolonte(){
			return ( in_array( CHARACTER_VOL_CONN_AVC_ID, $this->ConnaissancesAvancees ) ? CHARACTER_VOL_CONN_AVC_BONUS : 0 ) +
					( in_array( CHARACTER_VOL_CONN_MTR_ID, $this->ConnaissancesMaitre ) ? CHARACTER_VOL_CONN_MTR_BONUS : 0 );
		}
		
		public function GetSauvegardeVolonteStr(){
			$str = $this->GetSauvegardeVolonte() + $this->GetBonusVolonte();
			if( $this->GetBonusVolonte() != 0 ){
				$str .= " (" . $this->GetSauvegardeVolonte() .
						" + " . $this->GetBonusVolonte() . ")";
			}
			return $str;
		}
		
		public function GetForceArcanique(){ return $this->Intelligence; }
		public function GetForceDivine(){ return $this->Sagesse; }
	}
?>