<?php
	interface IRepository {
		public function Create( $opts = array() );
		public function Find( $id );
		public function Save( $obj );
		public function Delete( $id );
	}
?>