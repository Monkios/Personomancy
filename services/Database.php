<?php
	class Database {
		private $dbh = FALSE;
		private $sth = FALSE;
		
		public function __construct(){
			try {
				$this->Connect();
			} catch( PDOException $e ){
				Message::Fatale( "Connexion à la base de données impossible.", $e->getMessage() );
			}
		}
		
		public function Query( $sql, $params = array() ){
			if( !is_array( $params ) ){
				Message::Fatale( "Params doit être un array." );
			}
			
			if( $this->dbh !== FALSE ){
				try {
					$this->sth = $this->dbh->prepare( $sql );
					$this->sth->execute( $params );
				} catch( PDOException $e ) {
					Message::Fatale( "Incapable de lancer la requête sur la base de données.", $e->getMessage() );
				}
			} else {
				Message::Fatale( "La connexion à la base de données n'est pas initialisée." );
			}
		}
		
		public function GetResult(){
			if( $this->dbh !== FALSE && $this->sth !== FALSE ){
				return $this->sth->fetch( PDO::FETCH_ASSOC );
			} else {
				Message::Fatale( "Incapable d'atteindre le résultat de la requête." );
			}
		}
		
		public function GetInsertId(){
			if( $this->dbh !== FALSE && $this->sth !== FALSE ){
				$insert_id = $this->dbh->lastInsertId();

				// Retire le point qui apparait parfois
				if( $pos = strpos( $insert_id, '.' ) ){
					$insert_id = substr( $insert_id, 0, $pos );
				}
				return $insert_id;
			}
			Message::Fatale( "Une insertion doit être faite avant d'en retirer l'identifiant." );
		}
		
		private function Connect(){
			$this->dbh = new PDO(
					"mysql:host=" . DATABASE_SERVER . ";port=" . DATABASE_PORT . ";dbname=" . DATABASE_NAME,
					DATABASE_USER,
					DATABASE_PASSWORD
			);
			if( IS_DEV_MODE ){
				$this->dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			}
		}
		
		private static function InstantStatement( $sql, $params ){
			$db = new Database();
			$db->Query( $sql, $params );
			return $db;
		}
		
		// Returns nothing
		public static function Manipulation( $sql, $params = array() ){
			self::InstantStatement( $sql, $params );
		}
		
		// Returns 1 row
		public static function FetchOne( $sql, $params = array() ){
			return self::InstantStatement( $sql, $params )->GetResult();
		}
		
		// Returns ALL rows
		public static function FetchAll( $sql, $params = array() ){
			$r = array();
			
			$db = self::InstantStatement( $sql, $params );
			while( $result = $db->GetResult() ){
				$r[] = $result;
			}
			
			return $r;
		}
	}
?>