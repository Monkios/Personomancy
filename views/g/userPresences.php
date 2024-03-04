<?php
	set_time_limit( 300 );
	$pdf = new TCPDF( "L" );
	
	$pdf->SetCreator( MANCY_NAME );
	$pdf->SetAuthor( "Les Chroniques d'Akéras" );
	$pdf->SetTitle( "Les Chroniques d'Akéras - Feuille de présences" );
	$pdf->SetSubject( "Les Chroniques d'Akéras - Feuille de présences" );
	
	$pdf->SetPrintHeader( FALSE );
	$pdf->SetPrintFooter( FALSE );
	$pdf->SetMargins( 10, 10 );
	$pdf->SetFontSize( 8 );
	
	$headers = array( "joueur", "p. saison", "payé", "nouveau", "1 jour", "costume", "animateur", "personnage", "race", "faction", "notes" );
	$w = array( 45, 16, 15, 15, 15, 20, 20, 45, 25, 25, 30 );
	$h = 5;
	
	$pdf->AddPage();
	for( $i = 0; $i < count( $headers ); $i++ ) {
		$pdf->SetFillColor( 200, 200, 200 );
		$pdf->Cell( $w[ $i ], $h, $headers[ $i ], 1, 0, "C", 1 );
	}
	
	foreach( $joueurs as $joueur ){
		if( !$joueur->IsAnimateur ){
			$nb_persos = 0;
			foreach( $personnages as $personnage ){
				if( $personnage->joueur_id == $joueur->Id && $personnage->est_vivant && $personnage->est_cree ){
					$pdf->Ln( $h );
					if( $nb_persos == 0 ){
						$pdf->Cell( $w[ 0 ], $h, html_entity_decode( utf8_encode( $joueur->getFullName() ), ENT_QUOTES ), 1 );
						$pdf->Cell( $w[ 1 ], $h, $joueur->PasseSaison ? "Oui" : "", 1 );
						$pdf->Cell( $w[ 2 ], $h, "", 1 );
						$pdf->Cell( $w[ 3 ], $h, "", 1 );
						$pdf->Cell( $w[ 4 ], $h, "", 1 );
						$pdf->Cell( $w[ 5 ], $h, "", 1 );
						$pdf->Cell( $w[ 6 ], $h, "", 1 );
					} else {
						$pdf->Cell( $w[ 0 ] + $w[ 1 ] + $w[ 2 ] + $w[ 3 ] + $w[ 4 ] + $w[ 5 ] + $w[ 6 ], $h, "", 1 );
					}
				
					$pdf->Cell( $w[ 7 ], $h, html_entity_decode( utf8_encode( $personnage->nom ), ENT_QUOTES ), 1 );
					$pdf->Cell( $w[ 8 ], $h, utf8_encode( $personnage->race_nom ), 1 );
					$pdf->Cell( $w[ 9 ], $h, utf8_encode( $personnage->faction_nom ), 1 );
					$pdf->Cell( $w[ 10 ], $h, "", 1 );
					
					$nb_persos++;
				}
			}
		}
	}

	// Close and output PDF document inline
	$pdf->Output( "CdA_Presences_" . date( "YmdHi" ) . ".pdf", "I" );
?>