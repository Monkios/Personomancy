<?php
	interface IRepository {
		public function Create( array $opts = array() );
		public function Find( int $id );
		public function Save( GenericEntity $obj );
		public function Delete( int $id );
	}
?>