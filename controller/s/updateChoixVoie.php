<?php
	if( is_numeric( $_GET["i"] ) ){
		$choix_voie_repository = new ChoixVoieRepository();
		$choixVoie = $choix_voie_repository->Find( $_GET["i"] );
		$voies = $choix_voie_repository->GetVoies( $choixVoie );
		
		$list_voies = Dictionary::GetVoies();
		
		if( isset( $_POST["choix_voie_id"] ) && $_GET["i"] == $_POST["choix_voie_id"] ){
			if( isset( $_POST["save_choix_voie"] ) ){
				$choixVoie->nom = Security::FilterInput( $_POST["choix_voie_nom"] );
				$choixVoie->active = isset( $_POST["choix_voie_active"] );
				
				// ...
				
				if( !$choix_voie_repository->Save( $choixVoie ) ){
					Message::Erreur( "Une erreur s'est produite en mettant à jour les informations du choix de voie." );
				} else {
					Message::Notice( "Les informations du choix de voie ont été mises à jour." );
				}
			} elseif( isset( $_POST["add_voie"] ) ){
				if( is_numeric( $_POST["voie"] ) &&
							is_numeric( $_POST["voie"] ) &&
							!$choix_voie_repository->AddVoie( $choixVoie, $_POST["voie"] ) ){
					Message::Erreur( "Une erreur s'est produite en ajoutant la voie bonus au choix." );
				} else {
					Message::Notice( "La voie a été ajoutée." );
				}
			} elseif( isset( $_POST["delete_voie"] ) ){
				if( !$choix_voie_repository->RemoveVoie( $choixVoie, $_POST["delete_voie"] ) ){
					Message::Erreur( "Une erreur s'est produite en retirant la voie bonus au choix." );
				} else {
					Message::Notice( "La voie a été retirée." );
				}
			}
			
			header( "Location: ?s=super&a=updateChoixVoie&i=" . $choixVoie->id );
			die();
		}
	} else {
		Message::Fatale( "L'identifiant du choix de voie doit être numérique." );
	}
	
	$nav_links = array( "Liste des choix de voies" => "?s=super&a=listChoixVoies" );
	
	include "./views/top.php";
	include "./views/a/updateChoixVoie.php";
	include "./views/bottom.php";
?>