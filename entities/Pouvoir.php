<?php
	class Pouvoir extends GenericEntity {
		public $affiche = false;

		public $bonus_alerte = 0;
		public $bonus_constitution = 0;
		public $bonus_intelligence = 0;
		public $bonus_spiritisme = 0;
		public $bonus_vigueur = 0;
		public $bonus_volonte = 0;

		public $list_bonus_capacite = array();
		public $list_bonus_connaissance = array();
		public $list_bonus_voie = array();

		public $list_choix_capacite = array();
		public $list_choix_connaissance = array();
		public $list_choix_pouvoir = array();
	}
?>