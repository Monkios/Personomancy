<?php
	die( "OUTIL DÉSACTIVÉ" );
	
	header("Content-type: text/html; charset=utf-8");
	
	$pr = new PersonnageRepository();
	$characters = $pr->FindAll();
	
	$cs = new CharacterSheet();
	
	$suffix = " [REBUILD 2018]";
	
	$skip = $rebuild = 0;
	foreach( $characters as $c ){
		if( $c->est_vivant ){
			set_time_limit ( 5 );
			echo $c->nom . " ";
			
			if( !$c->est_cree && $c->px_totaux == CHARACTER_BASE_XP ){
				echo "Désactivation du personnage";
				
				$cs->UpdateBases( $c->id, $c->nom . $suffix, $c->alignement_id, $c->religion_id );
				$cs->Deactivate( $c->id );
				
				$skip++;
			} else {
				echo "REBUILD";
				
				$cs->Rebuild( $c->id, $suffix );
				
				$rebuild++;
			}
			
			echo "<br />\n";
		}
	}
	
	echo "SKIP = " . $skip . "<br />\n";
	echo "REBUILD = " . $rebuild . "<br />\n";
?>