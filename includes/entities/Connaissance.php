<?php
	class ConnaissanceType {
		const AVANCEE = "Avancée";
		const MAITRE = "Maître";
		const SYNERGIQUE = "Synergique";
		const LEGENDAIRE = "Légendaire";
		const UNKNOWN = "INCONNUE";
	}

	class Connaissance extends GenericEntity {
		public $cout = 0;
		
		public $prereq_capacite = 0;
		public $prereq_voie_primaire = 0;
		public $prereq_voie_secondaire = 0;

		public function GetConnaissanceType(){
			switch( $this->cout ){
				case CHARACTER_COST_CONN_LEGENDAIRE :
					if( $this->prereq_capacite != 0 ){ return ConnaissanceType::LEGENDAIRE; }
					break;
				case CHARACTER_COST_CONN_SYNERGIQUE :
					if( $this->prereq_voie_secondaire != 0 ){ return ConnaissanceType::SYNERGIQUE; }
					break;
				case CHARACTER_COST_CONN_MAITRE :
					return ConnaissanceType::MAITRE;
					break;
				case CHARACTER_COST_CONN_AVANCEE :
					return ConnaissanceType::AVANCEE;
					break;
			}
			return ConnaissanceType::UNKNOWN;
		}
	}
?>