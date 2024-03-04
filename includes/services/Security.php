<?php
	class Security {
		static function FilterInput( $str ){
			$str = trim( $str );
			$str = strip_tags( $str );
			$str = filter_var( $str, FILTER_SANITIZE_SPECIAL_CHARS );
			return $str;
		}
		
		static function FilterEmail( $str ){
			$str = trim( $str );
			$str = strip_tags( $str );
			$str = filter_var( $str, FILTER_VALIDATE_EMAIL );
			return $str;
		}
	}
?>