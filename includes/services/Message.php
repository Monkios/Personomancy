<?php
	class Message {
		public static $Fatale = 3;
		public static $Erreur = 2;
		public static $Notice = 1;
		public static $Debug  = 0;
		
		public static $LogLevel = 0;
		public static $LogFile  = "messages.log";
		
		public static function SetLogLevel( $level, $file = "" ){
			Message::$LogLevel = $level;
			if( !empty( $file ) ){
				Message::$LogFile = $file;
			}
		}
		
		public static function GetQueue(){
			if( isset( $_SESSION[ SESSION_KEY ][ "Messages" ] ) ){
				$tmp = $_SESSION[ SESSION_KEY ][ "Messages" ];
				self::EmptyQueue();
			} else {
				$tmp = array();
			}
			
			return $tmp;
		}
		
		public static function MsgInQueue(){
			if( isset( $_SESSION[ SESSION_KEY ][ "Messages" ] ) ){
				return count( $_SESSION[ SESSION_KEY ][ "Messages" ] );
			} else {
				return 0;
			}
		}
		
		private static function AddToQueue( $msg ){
			if( !isset( $_SESSION[ SESSION_KEY ][ "Messages" ] ) ){
				self::EmptyQueue();
			}
			$_SESSION[ SESSION_KEY ][ "Messages" ][] = $msg;
		}
		
		private static function EmptyQueue(){
			$_SESSION[ SESSION_KEY ][ "Messages" ] = array();
		}
		
		public static function Fatale( $msg, $note = "" ){
			self::Erreur( $msg, $note, FALSE );
			self::EmptyQueue();
			
			$txt = $msg;
			if( $note != "" ){
				$txt .= " / " . str_replace( "\n", "<br />", trim( print_r( $note, true ) ) );
				$txt .= " / " . $_SERVER[ "REMOTE_ADDR" ];
			}
			
			self::Log( $txt, Message::$Fatale );
			die( MANCY_NAME . " - " . $msg );
		}
		
		public static function Erreur( $msg, $note = "", $log = TRUE ){
			$txt = $msg;
			if( $note != "" ){
				$txt .= " / " . str_replace( "\n", "<br />", trim( print_r( $note, true ) ) );
			}
			
			if( $log ){
				self::Log( $txt, Message::$Erreur );
			}
			self::AddToQueue( "ERREUR - " . $msg );
		}
		
		public static function Notice( $msg ){
			self::Log( $msg, Message::$Notice );
			self::AddToQueue( $msg );
		}
		
		public static function Debug( $msg ){
			self::Log( $msg, Message::$Debug );
		}
		
		private static function Log( $msg, $level ){
			if( $level >= Message::$LogLevel ){
				$user = "ANON";
				if( isset( $_SESSION[ SESSION_KEY ] ) && isset( $_SESSION[ SESSION_KEY ][ "User" ] ) ){
					$joueur = $_SESSION[ SESSION_KEY ][ "User" ];
					if( $joueur && $joueur->Id !== FALSE ){
						$user = $joueur->Id;
					}
				}
			
				error_log( date( "Y-m-d H:i" ) . " - " . str_pad( $user, 4, "0", STR_PAD_LEFT ) . " - L" . $level . " - " . $msg . "\n", 3, self::$LogFile );
			}
		}
	}
?>