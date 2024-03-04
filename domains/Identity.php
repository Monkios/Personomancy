<?php
	class Identity {
		const IS_ACTIVE = "active";
		const IS_ANIM = "est_anim";
		const IS_ADMIN = "est_admin";
		
		private $player = FALSE;
		
		public function __construct( $player_id ){
			if( is_numeric( $player_id ) ){
				$p = Community::GetPlayer( $player_id );
			
				if( $p !== FALSE ){
					$this->player = $p;
					
					return TRUE;
				}
			}
			
			Message::Erreur( "Invalid Player ID." );
			return FALSE;
		}
		
		public function GetPlayer(){ return $this->player; }
		
		public static function GetConnectingPlayer( $email, $password ){
			$connecting = Community::GetPlayerByEMail( $email );
			
			if( $connecting ){
				$db = new Database();
				$identity = new Identity( $connecting->Id );
				
				$salty = self::HashPassword( $identity->player->Salt, $password );
				
				$sql = "SELECT id FROM joueur WHERE password = ?";
				$db->Query( $sql, array( $salty ) );
				if( $r = $db->GetResult() ){
					// Change the salt on each connection
					$identity->ChangePasswordTo( $password );
					
					if( $identity->player->IsActive ){
						$diff = date_diff( new DateTime( date( "Y-m-d", strtotime( $identity->player->DateModify ) ) ), new DateTime( "today" ) );
						$diff = $diff->m + ( $diff->y * 12 );					
					
						if( $diff > IDENTITY_TRIGGER_ACTIVATION ){
							$identity->SetPlayerAccess( Identity::IS_ACTIVE, FALSE );
							$identity->player->IsActive = FALSE;
						}
					}
					
					return $identity->player;
				} else {
					Message::Erreur( "Mot de passe invalide." );
				}
			} else {
				Message::Erreur( "Adresse de courriel inconnue." );
			}
			
			return FALSE;
		}
		
		public static function Create( $email, $firstname, $lastname ){
			if( !Community::GetPlayerByEMail( $email ) ){
				$db = new Database();
				
				$salt = "";
				$password_temp = Identity::GenerateRandomPassword();
				$login = str_replace( " ", "", strtoupper( substr( $firstname, 0, 1 ) ) . substr( ucfirst( $lastname ), 0, 5 ) . time() );
				
				$sql = "INSERT INTO joueur ( login, prenom, nom, courriel, date_naissance, salt, password, old_password, est_anim, est_admin, active, date_insert, date_modify )
						VALUES( ?, ?, ?, ?, '', 'salt', 'password', 'old_password', '0', '0', '0', NOW(), NOW() )";
				$db->Query( $sql, array( $login, $firstname, $lastname, $email ) );
				
				if( $joueur = Community::GetPlayerByEMail( $email ) ){
					$identity = new Identity( $joueur->Id );
					$identity->ChangePasswordTo( $password_temp );
					
					$activation_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?s=user&a=activate&m=" . urlencode( $email ) . "&k=" . urlencode( self::GenerateActivationKey( $email, $salt ) );
				
					$body  = "Une requête de création de compte vient d'être faite en votre nom.<br />\n";
					$body .= "<br />\n";
					$body .= "Pour activer votre compte, veuillez suivre le lien suivant : <a href='" . $activation_url . "'>Activation</a><br />\n";
					$body .= "<br />\n";
					$body .= "Lors de votre prochaine connexion, veuillez utiliser le mot de passe suivant : <b>" . $password_temp . "</b><br />\n";
					$body .= "Pour plus de sécurité, pensez à changer ce mot de passe dès que possible.<br />\n";
						
					return Email::Send( $email, "Nouveau compte", $body );
				}
			}
			return FALSE;
		}
		
		public function HasAccess( $access ){
			if( $this->player ){
				switch( $access ){
					case Identity::IS_ACTIVE :
						return $this->player->IsActive == 1;
						break;
					case Identity::IS_ANIM :
						return $this->player->IsAnimateur == 1;
						break;
					case Identity::IS_ADMIN :
						return $this->player->IsAdmin == 1;
						break;
					default :
						Message::Fatale( "Type d'accès inconnu.", $access );
				}
			}
			return FALSE;
		}
		
		public function SetPlayerAccess( $access, $value ){
			$return = FALSE;
			if( $this->player ){
				$db = new Database();
				
				$sql = "UPDATE joueur SET ";
				switch( $access ){
					case Identity::IS_ACTIVE :
						$sql .= "active";
						break;
					case Identity::IS_ANIM :
						$sql .= "est_anim";
						break;
					case Identity::IS_ADMIN :
						$sql .= "est_admin";
						break;
					default :
						Message::Fatale( "Type d'accès inconnu.", $access );
				}
				$sql .= " = ?, date_modify = NOW() WHERE id = ?";
				
				$db->Query( $sql, array( $value === TRUE, $this->player->Id ) );
				
				$return = TRUE;
			}
			
			return $return;
		}
		
		public function ChangeNameTo( $firstname, $lastname ){
			$db = new Database();
			$changes = array();
			$params = array();
			
			if( $this->player ){
				if( $firstname != "" ){
					$changes[] = " prenom = ?";
					$params[] = $firstname;
				}
				if( $lastname != "" ){
					$changes[] = " nom = ?";
					$params[] = $lastname;
				}
				
				if( count( $changes ) > 0 ){
					$changes[] = " date_modify = NOW()";
					
					$sql = "UPDATE joueur SET" . implode( ", ", $changes ) . " WHERE id = ?";
					$params[] = $this->player->Id;
					$db->Query( $sql, $params );
					
					$this->player->FirstName = $firstname;
					$this->player->LastName = $lastname;
					
					return TRUE;
				}
			} else {
				
			}
			
			return FALSE;
		}
		
		public function ChangeEmailTo( $email, $notify_user = TRUE ){
			$db = new Database();
			
			if( $this->player && $email != "" && !Community::GetPlayerByEMail( $email ) ){
				$this->SendEmailWillChangeEmail();
				
				$sql = "UPDATE joueur SET courriel = ?, date_modify = NOW() WHERE id = ?";
				$db->Query( $sql, array( $email, $this->player->Id ) );
				
				$this->player->Email = $email;
				
				return TRUE;
			}
			return FALSE;
		}
		
		public function ChangePasswordTo( $password ){
			if( $this->player ){
				$db = new Database();
				$new_salt = mt_rand();
				$hashed_password = self::HashPassword( $new_salt, $password );
			
				$sql = "UPDATE joueur SET password = ?, old_password = '', salt = ?, date_modify = NOW() WHERE id = ?";
				$db->Query( $sql, array( $hashed_password, $new_salt, $this->player->Id ) );
				
				$this->player->Salt = $new_salt;
				
				return TRUE;
			}
			return FALSE;
		}
		
		public function AssignCharacter( $character_id ){
			$pr = new PersonnageRepository();
			if( $this->player && $pr->Find( $character_id ) !== FALSE ){
				$sql = "UPDATE personnage SET joueur = ? WHERE id = ?";
				Database::Manipulation( $sql, array( $this->player->Id, $character_id ) );
				
				return TRUE;
			}
			return FALSE;
		}
		
		public function ChangePasseSaison( $add ){
			$return = FALSE;
			if( $this->player ){
				$db = new Database();
				
				$sql = "UPDATE joueur SET passe_saison = '" . ( $add ? 1 : 0 ) . "', date_modify = NOW() WHERE id = ?";
				
				$db->Query( $sql, array( $this->player->Id ) );
				
				$return = TRUE;
			}
			
			return $return;
		}
		
		public function SendValidationEmail(){
			if( $this->player ){
				$activation_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?s=user&a=activate&m=" . urlencode( $this->player->Email ) . "&k=" . urlencode( self::GenerateActivationKey( $this->player->Email, $this->player->Salt ) );
					
				$body  = "Une demande d'activation de compte vient d'être faite en votre nom.<br />\n";
				$body .= "<br />\n";
				$body .= "Pour activer votre compte, veuillez suivre le lien suivant : <a href='" . $activation_url . "'>Activation</a><br />\n";
				
				return Email::Send( $this->player->Email, "Activation de votre compte", $body );
			}
			return FALSE;
		}
		
		public function SendForgotEmail(){
			if( $this->player ){
				$password_temp = self::GenerateRandomPassword();
					
				$body  = "Une requête de mot de passe oublié vient d'être faite en votre nom.<br />\n";
				$body .= "<br />\n";
				$body .= "Lors de votre prochaine connexion, veuillez utiliser le mot de passe suivant : <b>" . $password_temp . "</b><br />\n";
				$body .= "Pour plus de sécurité, pensez à changer ce mot de passe dès que possible.<br />\n";
					
				if( Email::Send( $this->player->Email, "Mot de passe oublié ?", $body ) ){
					$this->ChangePasswordTo( $password_temp );
					return TRUE;
				}
			}
			return FALSE;
		}
		
		public function SendEmailWillChangeEmail(){
			if( $this->player ){
				$body  = "Une requête de changement d'adresse courriel vient d'être faite en votre nom.<br />\n";
				$body .= "<br />\n";
				$body .= "Lors de votre prochaine connexion, veuillez utiliser cette nouvelle adresse courriel.<br />\n";
					
				if( Email::Send( $this->player->Email, "Changement d'adresse courriel", $body ) ){
					return TRUE;
				}
			}
			return FALSE;
		}
		
		public function ActivateAccount( $key ){
			return (
					$this->player &&
					self::GenerateActivationKey( $this->player->Email, $this->player->Salt ) == $key &&
					$this->SetPlayerAccess( Identity::IS_ACTIVE, true )
			);
		}
		
		private static function GenerateActivationKey( $email, $salt ){
			return sha1( $email . $salt );
		}
		
		private static function HashPassword( $salt, $password ){
			return sha1( $salt . $password );
		}
		
		// http://stackoverflow.com/a/4356295
		private static function GenerateRandomPassword(){
			$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$charactersLength = strlen($characters);
			$randomString = "";
			for ($i = 0; $i < PASSWORD_GEN_LENGTH; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}
	}
?>